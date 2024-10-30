<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ayecode.io/
 * @since      1.0.0
 *
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/admin
 * @author     Stiofan O'Connor <info@ayecode.io>
 */
class Bbpress_Ms_Move_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bbpress_Ms_Move_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bbpress_Ms_Move_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bbpress-ms-move-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bbpress_Ms_Move_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bbpress_Ms_Move_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bbpress-ms-move-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add the admin menu to multisite settings menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu(){
		//add_menu_page( "page_title", "menu_title", 'manage_network_plugins', 'menu_slug', 'add_network_menu_1234_cb');
		add_menu_page( $this->plugin_name, 'bbPress Move', 'manage_network_plugins', $this->plugin_name, array(&$this, 'admin_page_html') );
		
	}

	public function admin_page_html(){
		/**
		 * The admin page html file.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bbpress-ms-move-admin-display.php';
	}
}
