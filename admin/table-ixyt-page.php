<?php

/**
 *
 * Table Admin Page
 *
 * @package    Ixyt_City_Events
 * @var $list_table
 */

if ( ! defined( 'ABSPATH' ) ){
    die( '-1' );
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">World City Events: IXYT</h1>
    <?php if( current_user_can( 'edit_posts' ) ) {
       echo '<a href="' . esc_html(menu_page_url( 'ixyt-page-new', false )) . '" class="page-title-action">'.esc_html(__('Add New','world-city-events-ixyt')).'</a>';
       echo '<a href="' . esc_html(IXYT_PLUGIN_URL) . '" target="_blank" class="page-title-action">'.esc_html(__('Instruction','world-city-events-ixyt')).'</a>';
    }
    ?>
    <?php if ( ! empty($_REQUEST['s'] ) ) {
        echo sprintf(
            '<span class="subtitle">'
            /* translators: %s: search keywords */
            . esc_html( __('Search results for &#8220;%s&#8221;', 'world-city-events-ixyt') )
            . '</span>' ,
             esc_html( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) )
        );
    }
    ?>
    <form method="get" action="">
        <input type="hidden" name="page" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) ); ?>"/>
        <?php $list_table->search_box(  __( 'Search', 'world-city-events-ixyt' ) , 'ixyt_page' ); ?>
        <?php $list_table->display(); ?>
    </form>

</div>