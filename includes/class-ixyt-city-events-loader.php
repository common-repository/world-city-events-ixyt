<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://arista.by
 * @since      1.0.0
 *
 * @package    Ixyt_City_Events
 * @subpackage Ixyt_City_Events/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Ixyt_City_Events
 * @subpackage Ixyt_City_Events/includes
 * @author     Arista.by <ex@email.ru>
 */


class Ixyt_City_Events_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = [];
		$this->filters = [];

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		];

		return $hooks;
	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

        require_once IXYT_PLUGIN_DIR . '/includes/class-ixyt-model.php';
        require_once IXYT_PLUGIN_DIR . '/includes/rest-remote-client.php';

        if ( is_admin() ) {
            require_once IXYT_PLUGIN_DIR . '/admin/partials/ixyt-city-events-admin-display.php';
        }

        $this->add_filter('plugin_action_links_ixyt-city-events/ixyt-city-events.php', $this, 'ixyt_plugin_action_links');

        $this->add_action('wp_enqueue_scripts', $this, 'ixyt_register_plugin_styles');

        $this->add_action('init', $this, 'register_post_type');

        $model = new IXYT_Model();

        add_shortcode( IXYT_SHORTCODE_TITLE, [ $model, 'ixyt_page_shortcode' ] );

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
		}
	}

    function ixyt_plugin_action_links( $links ) {

        $url = esc_url( add_query_arg(
            'page',
            'ixyt-page-new',
            get_admin_url() . 'admin.php'
        ) );
        // Create the link.
        $settings_link = "<a href='$url'>" . __( 'Settings','world-city-events-ixyt' ) . '</a>';

        array_unshift( $links, $settings_link );

        return $links;
    }

    function ixyt_register_plugin_styles() {

        wp_register_style( 'ixyt_style', plugins_url( 'ixyt-city-events/public/css/ixyt-city-events-public.css' ) );
        wp_enqueue_style( 'ixyt_style' );

        wp_register_style( 'ixyt_style', plugins_url( 'ixyt-city-events/admin/css/ixyt-city-events-admin.css' ) );
        wp_enqueue_style( 'ixyt_style' );

        wp_register_script( 'ixyt_script',
            plugins_url( 'ixyt-city-events/admin/js/ixyt-city-events-admin.js' ) );

        wp_enqueue_script( 'ixyt_script' );
    }

    function register_post_type(){
        IXYT_Model::custom_post_type();
    }
}

