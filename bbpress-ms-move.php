<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://ayecode.io/
 * @since             1.0.0
 * @package           Bbpress_Ms_Move
 *
 * @wordpress-plugin
 * Plugin Name:       Move bbPress Multisite
 * Plugin URI:        http://ayecode.io/
 * Description:       NETWORK ACTIVATE THIS PLUGIN: Allows to move a bbpress install from 1 website of a WordPress Network, to another website of the same MultiSite Network
 * Version:           1.0.0
 * Author:            Stiofan O'Connor
 * Author URI:        http://ayecode.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bbpress-ms-move
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bbpress-ms-move-activator.php
 */
function activate_bbpress_ms_move() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bbpress-ms-move-activator.php';
	Bbpress_Ms_Move_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bbpress-ms-move-deactivator.php
 */
function deactivate_bbpress_ms_move() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bbpress-ms-move-deactivator.php';
	Bbpress_Ms_Move_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bbpress_ms_move' );
register_deactivation_hook( __FILE__, 'deactivate_bbpress_ms_move' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bbpress-ms-move.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bbpress_ms_move() {

	$plugin = new Bbpress_Ms_Move();
	$plugin->run();

}
run_bbpress_ms_move();
