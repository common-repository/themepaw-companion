<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themepaw.com
 * @since             1.0.0
 * @package           tpaw
 *
 *
 * @wordpress-plugin
 * Plugin Name: Themepaw Companion
 * Description: A collection of addons or widgets for use in Elementor page builder. Elementor must be installed and activated.
 * Author: themepaw.com
 * Author URI: https://themepaw.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 0.7
 * Text Domain: themepaw-companion
 * Domain Path: languages
 *
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/*
Constants
------------------------------------------ */

/* Set plugin version constant. */
define( 'THEMEPAW_COMPANION_VERSION', '0.1' );

/* Set constant path to the plugin directory. */
define( 'THEMEPAW_COMPANION_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'THEMEPAW_COMPANION_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Plugin Addons Folder Path
define( 'THEMEPAW_COMPANION_ADDONS_DIR', plugin_dir_path( __FILE__ ) . 'widgets/' );

// Plugin Folder URL
define( 'THEMEPAW_COMPANION_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

// Assets Folder URL
define( 'THEMEPAW_COMPANION_ASSETS_URL', plugins_url( 'assets/', __FILE__ ) );

// Assets Folder URL
define( 'THEMEPAW_COMPANION_PLACEHOLDER_ICON', plugins_url( 'assets/images/cube.png', __FILE__ ) );


require_once( THEMEPAW_COMPANION_PATH . 'init.php' );
require_once( THEMEPAW_COMPANION_PATH . 'inc/helper-functions.php' );