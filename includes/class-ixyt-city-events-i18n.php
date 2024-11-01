<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://arista.by
 * @since      1.0.0
 *
 * @package    Ixyt_City_Events
 * @subpackage Ixyt_City_Events/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ixyt_City_Events
 * @subpackage Ixyt_City_Events/includes
 * @author     Arista.by <ex@email.ru>
 */
class Ixyt_City_Events_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'world-city-events-ixyt',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
