<?php

/**
 * @package    Ixyt_City_Events
 * IXYT notificator
 */

class IXYT_Notificator{

    public function notify(){
        $message =  sanitize_key( $_REQUEST['message'] );

        switch ($message) {
            case 'error':
                $message_text =  esc_html( __( "Some error.", 'world-city-events-ixyt' ) );
                $context = 'error';
                break;
            case 'created':
                $message_text =  esc_html( __( "Post successfully added.", 'world-city-events-ixyt' ) );
                $context = 'success';
                break;
            case 'edited':
                $message_text =  esc_html( __( "Post successfully edited.", 'world-city-events-ixyt' ) );
                $context = 'success';
                break;
            case 'deleted':
                $message_text =  esc_html( __( "Post(s) successfully deleted.", 'world-city-events-ixyt' ) );
                $context = 'success';
                break;
            default:
                $message_text = '';
                $context = '';
        }

        $class = 'notice notice-'.$context;

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message_text ) );
    }
}