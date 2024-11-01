<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://arista.by
 * @since             1.5.0
 * @package           Ixyt_City_Events
 *
 * @wordpress-plugin
 * Plugin Name:       World City Events: IXYT
 * Plugin URI:        https://ixyt.info/en/plugin
 * Description:       A unique WordPress plugin - get the latest events in your city! Increase your SEO rating and attract more visitors to your site with useful content. Free and easy to set up!
 * Version:           1.5.3
 * Author:            Arista.by
 * Author URI:        https://arista.by
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       world-city-events-ixyt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

define( 'IXYT_PLUGIN', __FILE__ );

define( 'IXYT_SHORTCODE_TITLE', 'ixyt-city-page' );

define( 'IXYT_URL', 'https://ixyt.info/' );

define( 'IXYT_PLUGIN_URL', 'https://ixyt.info/en/plugin' );

define( 'IXYT_PLUGIN_DIR', untrailingslashit( dirname( IXYT_PLUGIN ) ) );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IXYT_CITY_EVENTS_VERSION', '1.5.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ixyt-city-events-activator.php
 */
function ixyt_activate_city_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ixyt-city-events-activator.php';
	Ixyt_City_Events_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ixyt-city-events-deactivator.php
 */
function ixyt_deactivate_city_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ixyt-city-events-deactivator.php';
	Ixyt_City_Events_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ixyt_activate_city_events' );
register_deactivation_hook( __FILE__, 'ixyt_deactivate_city_events' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ixyt-city-events.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function ixyt_run_city_events() {
	$plugin = new Ixyt_City_Events();
	$plugin->run();
}

ixyt_run_city_events();
