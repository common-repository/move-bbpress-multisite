<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://ayecode.io/
 * @since      1.0.0
 *
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/includes
 * @author     Stiofan O'Connor <info@ayecode.io>
 */
class Bbpress_Ms_Move_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bbpress-ms-move',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
