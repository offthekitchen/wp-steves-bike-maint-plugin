<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/public
 */

/**
 * Public assets for BikePress.
 *
 * @package    BikePress
 * @subpackage BikePress/public
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class BikePress_Public {

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $plugin_name;

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $version;

	/**
	 * @since 1.0.0
	 * @param string $plugin_name Plugin slug.
	 * @param string $version     Plugin version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register public styles (handle matches shortcode enqueue).
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/bikepress-public.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register public scripts (handle matches shortcode enqueue).
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/bikepress-public.js',
			array( 'jquery' ),
			$this->version,
			true
		);
	}
}
