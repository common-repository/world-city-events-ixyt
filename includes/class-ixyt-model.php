<?php

class IXYT_Model{

    const post_type = 'ixyt_events_page';

	private static $found_items = 0;

    public $id;
    public $title;
    public $locale;
    public $post_content;
    public $content;

    private static $current = null;

    public function get_country(){
        $data = explode( '_', $this->content );
        if( array_key_exists( 0 , $data ) ){
            return $data[0];
        }
        return '';
    }

    public function get_city(){
        $data = explode( '_', $this->content );
        if( array_key_exists( 1 , $data ) ){
            return $data[1];
        }
        return '';
    }

    public function get_country_loc(){
        $data = explode( '_', $this->content );
        if( array_key_exists( 2 , $data ) ){
            return $data[2];
        }
        return $this->get_country();
    }

    public function get_city_loc(){
        $data = explode( '_', $this->content );
        if( array_key_exists( 3 , $data ) ){
            return $data[3];
        }
        return $this->get_city();
    }

    public static function custom_post_type(){
        register_post_type( self::post_type,
            array(
                'label'  => esc_html__( 'IXYT city events', 'world-city-events-ixyt' ),
                'supports' => [ 'title', 'page-attributes', 'author' ],
                'rewrite' => false,
                'query_var' => false,
                'public' => false,
                'capability_type' => 'page',
            )
        );
    }
	public function initial() {
		return empty( $this->id );
	}

    public function delete() {
        if ( $this->initial() ) {
            return;
        }

        if ( wp_delete_post( $this->id, true ) ) {
            $this->id = 0;
            return true;
        }

        return false;
    }

    public function get_shortcode(){
        return '[' . IXYT_SHORTCODE_TITLE . ' id=' . $this->id . ']';
    }

    public function ixyt_page_shortcode( $atts = []) {
        shortcode_atts( [
            'id' => '',
        ], $atts );

        if ( empty( $atts['id'] ) ) {
            return '<h3 style="text-align: center; color: red">'.__( 'ID not found.', 'world-city-events-ixyt' ).'</h3>';
        }
        $post = get_post( $atts['id'] );

        if( ! $post ){
            return '<h3 style="text-align: center; color: red">'.__( 'Current post was deleted or does not exist.', 'world-city-events-ixyt' ).'</h3>';
        }

        if( $post->post_type !== self::post_type ){
            return '<h3 style="text-align: center; color: red">'.__( 'Wrong post type.', 'world-city-events-ixyt' ).'</h3>';
        }

        $rest_attr = explode( '_', $post->post_content );

        if( ! array_key_exists( 0, $rest_attr ) && ! array_key_exists( 1, $rest_attr ) ){
            return '<h3 style="text-align: center; color: red">'.__( 'Problems with post content.', 'world-city-events-ixyt' ).'</h3>';
        }

        $locale = '';
        switch ( get_locale() ){
            case 'ru_RU':
                $locale = 'ru';
                break;
            case 'es_ES':
                $locale = 'sp';
                break;
            case 'uk':
                $locale = 'ua';
                break;
            case 'fr_FR':
                $locale = 'fr';
                break;
            case 'it_IT':
                $locale = 'it';
                break;
            case 'de_DE':
                $locale = 'de';
                break;
            default:
                $locale = 'en';

        }

        $country = $rest_attr[0];

        $city_name = (array_key_exists(3,$rest_attr)&&$rest_attr[3]!='')?$rest_attr[3]:$rest_attr[1];

        $city = $rest_attr[1];

        $json = ixyt_get_city_page( $rest_attr[0], $rest_attr[1] );

        $events = $json->content;

        if( ! $json || ! $json->content ){
            ob_start();

            include( IXYT_PLUGIN_DIR . '/includes/templates/ixyt-page-not-found-temlate.php' );

            return ob_get_clean();
        }

        ob_start();

        include( IXYT_PLUGIN_DIR . '/includes/templates/ixyt-page-template.php' );

        return ob_get_clean();
    }

    public static function save($args){

        $post_data = [
            'post_title'    => $args['title'],
            'post_content'  => $args['country'].'_'.$args['city'].'_'.$args['country_loc'].'_'.$args['city_loc'],
            'post_type'=>   self::post_type,
            'post_status'   => 'publish',
            'post_author'   => wp_get_current_user()->id,
            'post_category' => array( 8,39 )
        ];

        if( -1 != $args['id'] ){
            $post_data  += ['ID'=>$args['id']];
        }

        return wp_insert_post( $post_data );
    }

	public static function count() {
		return self::$found_items;
	}

    public static function find_one($post_id){
        $post = get_post( $post_id );

        if ( ! $post
            or self::post_type != get_post_type( $post ) ) {
            return false;
        }

        $current_post = self::$current = new self( $post );
        $current_post->id = $post->ID;
        $current_post->title = $post->post_title;
        $current_post->content = $post->post_content;

        return $current_post;
    }

    public static function find( $args = '' ) {
        $defaults = [
            'post_status' => 'any',
            'posts_per_page' => -1,
            'offset' => 0,
            'orderby' => 'ID',
            'order' => 'ASC',
        ];

        $args = wp_parse_args( $args, $defaults );

        $args['post_type'] = self::post_type;

        $q = new WP_Query();
        $posts = $q->query( $args );

        self::$found_items = $q->found_posts;

		return $posts;

        $objs = array();

        foreach ( (array) $posts as $post ) {
            $objs[] = new self( $post );
        }

        return $objs;
    }
}
