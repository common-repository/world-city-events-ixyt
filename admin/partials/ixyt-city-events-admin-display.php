<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://arista.by
 * @since      1.0.0
 *
 * @package    Ixyt_City_Events
 * @subpackage Ixyt_City_Events/admin/partials
 */

if ( ! defined('ABSPATH')) {
    die('-1');
}

add_action(
    'admin_menu',
    'ixyt_admin_menu',
    9, 0
);

function ixyt_admin_menu()
{

    add_filter(
        'set_screen_option_ixyt_per_page',
        function ($result, $option, $value) {
            $ixyt_screens = [
                'ixyt_pages_per_page',
            ];

            if (in_array($option, $ixyt_screens)) {
                $result = $value;
            }

            return $result;
        },
        10, 3
    );

    do_action('ixyt_admin_menu');

    add_menu_page(
        __('World City Events: IXYT', 'world-city-events-ixyt'),
        __('World City Events: IXYT', 'world-city-events-ixyt'),
        'manage_options',
        'ixyt-page',
        'ixyt_manage_menu_page',
        'dashicons-admin-multisite',
        30
    );

    $edit = add_submenu_page('ixyt-page',
        __('IXYT edit', 'world-city-events-ixyt'),
        __('World City Events: IXYT', 'world-city-events-ixyt'),
        'manage_options',
        'ixyt-page',
        'ixyt_manage_menu_page'
    );

    add_action('load-' . $edit, 'ixyt_load_admin', 10, 0);

    $create = add_submenu_page('ixyt-page',
        __('Add New City Events', 'world-city-events-ixyt'),
        __('Add New', 'world-city-events-ixyt'),
        'manage_options',
        'ixyt-page-new',
        'ixyt_add_menu_page'
    );

    add_action('load-' . $create, 'ixyt_load_admin', 10, 0);
}

function ixyt_current_action()
{

    if (isset($_REQUEST['action']) and -1 != $_REQUEST['action']) {
        return sanitize_text_field($_REQUEST['action']);
    }

    if (isset($_REQUEST['action2']) and -1 != $_REQUEST['action2']) {

        return sanitize_text_field($_REQUEST['action2']);
    }
    return false;
}

function ixyt_load_admin()
{

    global $plugin_page;

    if (isset($_REQUEST['message']) && ! empty($_REQUEST['message'])) {

        require_once IXYT_PLUGIN_DIR . '/admin/includes/class-ixyt-notificator.php';

        $notificator = new IXYT_Notificator();

        add_action('admin_notices', [$notificator, 'notify'], 10, 2);
    }


    $action = ixyt_current_action();

    if ('save' == $action) {

        $id = isset($_POST['post_ID']) ? sanitize_text_field( $_POST['post_ID'] ) : '-1';
        wp_verify_nonce('ixyt-save-page');

        if ( ! current_user_can('edit_posts')) {
            wp_die(
               esc_html( __("You are not allowed to edit this item.", 'world-city-events-ixyt') )
            );
        }

        $args['id'] = $id;

        $args['title'] = isset( $_POST['ixyt-title'] )
            ? sanitize_text_field( $_POST['ixyt-title'] ) : null;

        $args['country'] = isset( $_POST['ixyt-country-meta'] )
            ? sanitize_text_field( $_POST['ixyt-country-meta'] ) : null;

        $args['city'] = isset( $_POST['ixyt-city-meta'] )
            ? sanitize_text_field( $_POST['ixyt-city-meta'] ) : null;

        $args['country_loc'] = isset( $_POST['ixyt-country_loc'] )
            ? sanitize_text_field( $_POST['ixyt-country_loc'] ) : null;

        $args['city_loc'] = isset( $_POST['ixyt-city_loc'] )
            ? sanitize_text_field( $_POST['ixyt-city_loc'] ) : null;

        $new_id = IXYT_Model::save( $args );

        if ( ! empty( $new_id ) ) {
            $query['message'] = 'created';
        }

        if ( ! empty( $new_id ) && ! empty( $id ) ) {
            $query['message'] = 'edited';
        }

        wp_safe_redirect( add_query_arg( $query, menu_page_url( 'ixyt-page', false) ) );
    }

    if ('delete' == $action) {

        if ( ! empty($_POST['post_ID'] ) ) {
            check_admin_referer( 'ixyt-delete-page' . sanitize_text_field( $_POST['post_ID'] ) );
        } elseif ( ! is_array($_REQUEST['post'])) {
            check_admin_referer( 'ixyt-delete-page' . sanitize_text_field( $_REQUEST['post'] ) );
        } else {
            check_admin_referer( 'bulk-posts' );
        }

        $posts = empty($_POST['post_ID'])
            ? rest_sanitize_array( $_REQUEST['post'] )
            : (array)sanitize_text_field( $_POST['post_ID'] );

        $deleted = 0;

        foreach ( $posts as $post ) {
            $post = IXYT_Model::find_one( sanitize_text_field( $post ) );

            if (empty($post)) {
                continue;
            }

            if ( ! current_user_can('delete_posts', $post->id)) {
                wp_die(
                    __("You are not allowed to delete this item.", 'world-city-events-ixyt')
                );
            }

            if ( ! $post->delete()) {
                wp_die(__("Error in deleting.", 'world-city-events-ixyt'));
            }

            $deleted += 1;
        }

        $query = [];

        if ( ! empty($deleted)) {
            $query['message'] = 'deleted';
        }

        wp_safe_redirect(add_query_arg($query, menu_page_url('ixyt-page', false)));

    }
}

function ixyt_manage_menu_page()
{
    if ( ! class_exists('IXYT_List_Table')) {
        require_once IXYT_PLUGIN_DIR . '/admin/includes/class-ixyt-list-table.php';
    }

    if ( isset( $_REQUEST['action'] ) and 'edit' === $_REQUEST['action'] ) {
        $post = IXYT_Model::find_one( sanitize_text_field( $_REQUEST['post'] ) );

        require_once IXYT_PLUGIN_DIR . '/admin/save-ixyt-page.php';

        return;
    }

    $list_table = new IXYT_List_Table();

    $list_table->prepare_items();

    require_once IXYT_PLUGIN_DIR . '/admin/table-ixyt-page.php';
}

function ixyt_add_menu_page()
{

    $post = new IXYT_Model();

    require_once IXYT_PLUGIN_DIR . '/admin/save-ixyt-page.php';
}
