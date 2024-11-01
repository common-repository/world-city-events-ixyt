<?php

/**
 * @package    Ixyt_City_Events
 * IXYT table
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class IXYT_List_Table extends WP_List_Table {


	public function __construct() {
		parent::__construct( [
			'singular' => 'post',
			'plural' => 'posts',
			'ajax' => false,
		] );
	}

	public function prepare_items() {
	    $current_screen = get_current_screen();
		$per_page = 10;

		$args = [
			'posts_per_page' => $per_page,
			'orderby' => 'title',
			'order' => 'ASC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page,
		];

        if ( ! empty( $_REQUEST['s'] ) ) {
            $args['s'] = sanitize_text_field( $_REQUEST['s'] );
        }

		$this->items = IXYT_Model::find( $args );

		$total_items = IXYT_Model::count();
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page,
		] );


		$columns = $this->get_columns();

		$this->_column_headers = array($columns);

	}

	public function get_columns() {
		return [
			'cb' => __( 'checkbox', 'world-city-events-ixyt' ),
			'title'=>__( 'Title', 'world-city-events-ixyt' ),
			'author'=>__( 'Author', 'world-city-events-ixyt' ),
            'country'=>__( 'Country', 'world-city-events-ixyt' ),
            'city_page'=>__( 'City page', 'world-city-events-ixyt' ),
			'shortcode'=>__( 'Short Code', 'world-city-events-ixyt' )
		];
	}

	protected function get_sortable_columns() {
		$columns = [
			'title' => array ('title', true ),
			'author' => array( 'author', false ),
			'date' => array( 'date', false ),
		];

		return $columns;
	}

	protected function get_bulk_actions() {
		$actions = [
			'delete' => __( 'Delete', 'world-city-events-ixyt' ),
		];

		return $actions;
	}

	protected function column_default( $item, $column_name ) {
		return '';
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->ID
		);
	}

    public function column_country( $item ) {
        $data = explode( '_', $item->post_content );
        if( array_key_exists( 2 , $data ) ){
            return esc_html($data[2]);
        }else if(array_key_exists( 0 , $data )){
            return esc_html($data[0]);
        }
        return '-';
    }

    public function column_city_page( $item ) {
        $data = explode( '_', $item->post_content );
        if( array_key_exists( 3 , $data ) ){
            return esc_html($data[3]);
        }else if(array_key_exists( 1 , $data )){
            return esc_html($data[1]);
        }
        return '-';
    }

	public function column_title( $item ) {

		$edit_link = add_query_arg(
			[
				'post' => absint( $item->ID ),
				'action' => 'edit',
                'page'=> 'ixyt-page'
			]

		);

		$output = sprintf(
			'<a class="row-title" href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( $edit_link ),
			esc_attr( sprintf(
			/* translators: %s: title of contact form */
				__( 'Edit &#8220;%s&#8221;', 'world-city-events-ixyt' ),
				$item->post_title
			) ),
			esc_html( $item->post_title )
		);

		return $output;
	}

	public function column_author( $item ) {
		$post = get_post( $item->ID );

		if ( ! $post ) {
			return;
		}

		$author = get_userdata( $post->post_author );

		if ( false === $author ) {
			return;
		}

		return esc_html( $author->display_name );
	}

	public function column_shortcode( $item ) {
		$shortcodes = [ $item->shortcode ];

		$output = '';

		foreach ( $shortcodes as $shortcode ) {
			$output .= "\n" . '<span class="shortcode"><input type="text"'
				. ' onfocus="this.select();" readonly="readonly"'
				. ' value="['.IXYT_SHORTCODE_TITLE.' id='.$item->ID.']"'
				. ' class="large-text code" /></span>';
		}

		return trim( $output );
	}

}
