<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/admin
 * @author     Your Name <email@example.com>
 */
class Steves_Bike_Maintenance_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $Steves_Bike_Maintenance    The ID of this plugin.
	 */
	private $Steves_Bike_Maintenance;

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
	 * @param      string    $Steves_Bike_Maintenance       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Steves_Bike_Maintenance, $version ) {

		$this->Steves_Bike_Maintenance = $Steves_Bike_Maintenance;
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
		 * defined in Steves_Bike_Maintenance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Steves_Bike_Maintenance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->Steves_Bike_Maintenance, plugin_dir_url( __FILE__ ) . 'css/steves-bike-maintenance-admin.css', array(), $this->version, 'all' );

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
		 * defined in Steves_Bike_Maintenance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Steves_Bike_Maintenance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->Steves_Bike_Maintenance, plugin_dir_url( __FILE__ ) . 'js/steves-bike-maintenance-admin.js', array( 'jquery' ), $this->version, false );

	}

}
