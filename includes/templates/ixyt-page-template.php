<?php

/**
 *
 * IxytPage
 *
 * @package    Ixyt_City_Events
 * @var $list_table
 */


if ( ! defined('ABSPATH')) {
    die('-1');
}
?>

<section class="ixyt_events--container">
    <h3>
        <img src="<?php echo esc_html( plugins_url( 'world-city-events-ixyt/assets/logo.png' ) ); ?>"><span><?php echo esc_html( $city_name ); ?>: <?php echo esc_html ( __('addresses and names of events in one table', 'world-city-events-ixyt' ) ); ?></span>
    </h3>
    <div class="ixyt_events--list">
        <?php foreach ( $events as $event ): ?>
            <article class="ixyt_events--item">
                <a target="_blank" href="<?php echo esc_url( IXYT_URL . $locale . '/events/' . $event->id ); ?>">
                    <div class="ixyt_events--item-image"><img
                                src="<?php echo $event->image ? esc_url($event->image) : esc_url( plugins_url( 'world-city-events-ixyt/assets/default-ixyt-image-'.rand(1,3).'.jpg' ) ); ?>"
                                alt="event-image"></div>
                    <div class="ixyt_events--item-desc">
                        <time class="ixyt_events--item-date"
                              datetime="<?php echo esc_attr( gmdate('d.m.Y h:i', strtotime( $event->date_start ) ) ); ?>"><?php echo esc_attr( gmdate( 'd.m.Y h:i', strtotime( $event->date_start ) ) ); ?></time>
                        <h4><?php echo esc_html( $event->name ); ?></h4>
                        <p><?php echo  esc_html( mb_substr( strip_tags( html_entity_decode( $event->description ) ), 0, 100 ) ); ?></p>
                    </div>
                </a>
                <div class="ixyt_events--item-links">
                    <a class="ixyt_events--item-more" target="_blank"
                       href="<?php echo esc_url(IXYT_URL. $locale . '/events/' . $event->id); ?>"><?php echo esc_html ( __('READ MORE', 'world-city-events-ixyt' ) ); ?>
                        <img width="14px" height="14px" src="<?php echo esc_url( plugins_url( 'world-city-events-ixyt/assets/arrow.svg') ); ?>"
                             alt="arrow">
                    </a>
                    <a class="ixyt_events--item-ticket" rel="nofollow" target="_blank"
                       href="<?php echo esc_url( $event->source_url ); ?>"><?php echo esc_html ( __('Ticket', 'world-city-events-ixyt' ) ); ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <a target="_blank" class="ixyt_events-more" href="<?php echo esc_url( IXYT_URL. $locale.'/'.$country.'/'.$city ); ?>"><?php echo esc_html( __('MORE EVENTS', 'world-city-events-ixyt' ) ); ?></a>
</section>
