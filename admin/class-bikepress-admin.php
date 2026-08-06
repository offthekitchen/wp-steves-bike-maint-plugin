<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    BikePress
 * @subpackage BikePress/admin
 */
class BikePress_Admin {

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
	 * Admin styles are loaded via bikepress_enqueue_admin_assets() to avoid duplicates.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_styles( $hook = '' ) {
		// No-op: CSS handled in wp-bikepress.php for BikePress screens only.
	}

	/**
	 * Enqueue admin JS on BikePress screens only.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_scripts( $hook = '' ) {
		if ( ! function_exists( 'bikepress_is_plugin_admin_screen' ) || ! bikepress_is_plugin_admin_screen( $hook ) ) {
			return;
		}

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/bikepress-admin.js',
			array( 'jquery' ),
			$this->version,
			false
		);
	}
}
