<?php
/**
 * Fired during plugin deactivation.
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/includes
 */

/**
 * Deactivation is non-destructive: tables, demo data, and DB version remain.
 *
 * @since      1.0.0
 * @package    BikePress
 * @subpackage BikePress/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class BikePress_Deactivator {

	/**
	 * Run on plugin deactivation.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		// Intentionally empty: deactivation must not delete data or options.
		// Schema version and tables are removed only on uninstall.
	}
}
