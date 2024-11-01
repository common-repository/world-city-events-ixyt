<?php

/**
 *
 * Ixyt 404 page
 *
 * @package    Ixyt_City_Events
 * @var $list_table
 */

if ( ! defined( 'ABSPATH' ) ) {
    die('-1');
}
?>

<section class="ixyt_events--container">
    <h3>
        <img src="<?php echo esc_html( plugins_url( 'world-city-events-ixyt/assets/logo.png' ) ); ?>"><span><?php echo esc_html( $city_name ); ?> : <?php echo esc_html( __( 'addresses and names of events in one table', 'world-city-events-ixyt' ) ); ?></span>
    </h3>
    <div class="ixyt_events--list" style="justify-content: center; font-size: 30px">
        <?php echo __('Events not found in current period...','world-city-events-ixyt')?>
    </div>
    <a target="_blank" class="ixyt_events-more"
       href="<?php echo esc_html( IXYT_URL ); ?>"><?php echo esc_html( __('MORE EVENTS', 'world-city-events-ixyt' ) ); ?></a>
</section>
