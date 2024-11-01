<?php

/**
 *
 * Save Admin Page
 *
 * @package    Ixyt_City_Events
 * @var $post
 */

if ( ! defined('ABSPATH')) {
    die('-1');
}

function ixyt_admin_save_button( $post_id )
{
    static $button = '';

    if ( ! empty( $button ) ) {
        return $button;
    }

    $nonce = wp_create_nonce( 'ixyt-save_' . $post_id );

    $onclick = sprintf(
        "this.form._wpnonce.value = '%s';"
        . " this.form.action.value = 'save';"
        . " return true;",
        $nonce);

    $button = sprintf(
        '<input type="submit" class="button-primary" name="ixyt-save" value="%1$s" onclick="%2$s" />',
        esc_attr( __( 'Save', 'world-city-events-ixyt' ) ),
        $onclick);

    return $button;
}

?>

<div class="wrap" id="ixyt-contact-form-editor">

    <h1 class="wp-heading-inline">
        <?php
        if ( $post->initial() ) {
            echo esc_html( __( 'Add New Page', 'world-city-events-ixyt' ) );
        } else {
            echo esc_html( __( 'Edit page', 'world-city-events-ixyt' ) );
        }
        ?>
    </h1>
    <form class="ixyt-form" method="post"
          action="<?php echo esc_url( add_query_arg( ['post' => -1, 'action' => 'save'], menu_page_url( 'ixyt-page', false ) ) ); ?>"
          id="ixyt-admin-form-element">
        <?php
        if ( current_user_can('edit_posts') ) {
            wp_nonce_field( 'ixyt-save-page' );
        }
        ?>
        <input type="hidden" id="post_ID" name="post_ID" value="<?php echo esc_attr( (int)$post->id ); ?>"/>
        <input type="hidden" id="ixyt_url" value="<?php echo esc_attr(IXYT_URL); ?>"/>
        <input type="hidden" id="ixyt_locale" value="<?php echo esc_attr(get_locale()); ?>"/>
        <input type="hidden" id="country-meta"
               value="<?php echo esc_attr( $post->get_country() ); ?>"/>
        <input type="hidden" id="city-meta"
               value="<?php echo esc_attr( $post->get_city() ); ?>"/>
        <input type="hidden" id="country-meta_loc" name="ixyt-country_loc"
               value="<?php echo esc_attr( $post->get_country_loc() ); ?>"/>
        <input type="hidden" id="city-meta_loc" name="ixyt-city_loc"
               value="<?php echo esc_attr( $post->get_city_loc() ); ?>"/>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div id="titlediv">
                        <div id="titlewrap">
                            <input type="text" name="ixyt-title" placeholder="<?php echo esc_html ( __('add title', 'world-city-events-ixyt') ); ?>"
                                   required value="<?php echo $post->title ? esc_html( $post->title ) : ''; ?>" size="30" id="title" spellcheck="true"
                                   autocomplete="off">
                        </div>
                        <input type="hidden" id="samplepermalinknonce" name="samplepermalinknonce" value="25fbd72e58">
                    </div>
                </div>
                <div id="postbox-container-1" class="postbox-container">
                    <div id="submitdiv" class="postbox">
                        <h3>action</h3>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing-actions">

                                    <div class="hidden">
                                        <input type="submit" class="button-primary" name="ixyt-save" value="Сохранить">
                                    </div>

                                </div><!-- #minor-publishing-actions -->

                                <div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <span class="spinner"></span>
                                        <?php echo ixyt_admin_save_button( esc_html( $post->id ) ); ?></div>
                                    <div class="clear"></div>
                                </div><!-- #major-publishing-actions -->
                            </div><!-- #submitpost -->
                        </div>
                    </div><!-- #submitdiv -->

                    <div id="informationdiv" class="postbox">
                        <h3><?php echo esc_html( __('Help block', 'world-city-events-ixyt' ) ); ?></h3>
                        <div class="inside">
                            <p><?php $link = '<a href="'.esc_url(IXYT_PLUGIN_URL).'" target="_blank">'.IXYT_PLUGIN_URL.'</a>';  printf(esc_html__( 'More info %s', 'world-city-events-ixyt' ),$link); ?></p>
                        </div>
                    </div><!-- #informationdiv -->
                </div
            </div>
            <select id="ixyt-country-select" name="ixyt-country-meta" required></select>

            <select style="<?php echo isset($post->content) ? '' : 'display: none' ?>" id="ixyt-city-select"
                    name="ixyt-city-meta" required></select>
            <p class="description">
                <?php if ( $post->id ): ?>
                <label for="ixyt-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", 'world-city-events-ixyt' ) ); ?></label>
                <span class="shortcode wp-ui-highlight"><input type="text" id="ixyt-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $post->get_shortcode() ); ?>" /></span>
                <?php endif; ?>
            </p>
            <div id="action">
                <?php echo ixyt_admin_save_button( $post->id ) ?>
            </div>
    </form>
</div>
