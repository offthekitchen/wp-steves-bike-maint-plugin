<?php

/**
 *
 * @link              http://www.offthekitchen.com
 * @since             1.0.0
 * @package           Steves_Bike_Maintenance_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Steve's Bike Maintenance
 * Plugin URI:        http://www.offthekitchen.com
 * Description:       A simple plugin to track my bikes and maintenace 
 * Version:           1.0.0
 * Author:            Off the Kitchen
 * Author URI:        http://www.offthekitchen.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       steves-bike-maintenance
 * Domain Path:       /languages
 * 
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('STEVES_BIKE_MAINTENANCE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_steves_bike_maintenance()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-steves-bike-maintenance-activator.php';
	Steves_Bike_Maintenance_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_steves_bike_maintenance()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-steves-bike-maintenance-deactivator.php';
	Steves_Bike_Maintenance_Deactivator::deactivate();
}

function save_output_buffer_to_file()
{
	file_put_contents(
		ABSPATH . 'wp-content/plugins/activation_output_buffer.html'
		,
		ob_get_contents()
	);
}

/**
 * This function creates content for the bike list shortcode
 */
function sbm_bike_list($atts)
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-bike.php';

	global $wpdb;

	$bike_plugin_url = plugin_dir_url(__FILE__);

	$bikes_table_name = $wpdb->prefix . 'bikes';
	$maintenance_table_name = $wpdb->prefix . 'bike_maintenance';
	$specs_table_name = $wpdb->prefix . 'bike_specs';
	$status_table_name = $wpdb->prefix . 'bike_status';

	$aBikes = $wpdb->get_results("SELECT $bikes_table_name.*, $status_table_name.bike_status FROM $bikes_table_name INNER JOIN $status_table_name ON $bikes_table_name.bike_status_id = $status_table_name.id");
	$aMaintenance = $wpdb->get_results("SELECT * FROM $maintenance_table_name");
	$aSpecs = $wpdb->get_results("SELECT * FROM $specs_table_name");

	// BIKES LIST SECTION
	$Content = '<section id="bike-list" class="bike-section fade-in">';
	$Content .= '<h1 class="bike-list-title">BIKES</h1>';
	$Content .= '</h1>';
	foreach ($aBikes as $oBike) {
		$Content .= '<article class="bike bike-data">';
		$image_attributes = wp_get_attachment_image_src($oBike->bike_image_id);
		$sImageTag = '';
		if ($image_attributes) {
			$sImageTag = "<img src=\"{$image_attributes[0]}\" width=\"{$image_attributes[1]}\" height=\"{$image_attributes[2]}\" class=\"bike-image\" />";
		} else {
			$sImageTag = "<img src=\"{$bike_plugin_url}img/default-bike.jpg\" class=\"bike-image\" />";
		}
		$Content .= $sImageTag;
		$Content .= '<div class="bike-info">';
		$Content .= '<div class="bike-header">';
		$Content .= "<h2 class=\"bike-title\">{$oBike->bike_name}</h2>";
		$Content .= '<div class="icons-wrapper">';
		$Content .= "<a href=\"javascript:void(0);\" onclick=\"showSection('maintenance', {$oBike->id}, '{$oBike->bike_name}');\">";
		$Content .= "<img src=\"{$bike_plugin_url}img/icon-maint.png\" class=\"bike-icon\" />";
		$Content .= '<p class="icon-subtext">Matinenance</p>';
		$Content .= '</a>';
		$Content .= "<a href=\"javascript:void(0);\" onclick=\"showSection('specs', {$oBike->id}, '{$oBike->bike_name}');\">";
		$Content .= "<img src=\"{$bike_plugin_url}img/icon-specs.png\" class=\"bike-icon\" />";
		$Content .= '<p class="icon-subtext">Specs</p>';
		$Content .= '</a>';
		$Content .= '</div>';
		$Content .= '</div>';
		$Content .= '<div class="bike-specs">';
		$Content .= "<div class=\"bike-make bike-detail\"><b>MAKE:</b> {$oBike->bike_make}</div>";
		$Content .= "<div class=\"bike-model bike-detail\"><b>MODEL:</b> {$oBike->bike_model} </div>";
		$Content .= "<div class=\"bike-status bike-detail\"><b>STATUS:</b> {$oBike->bike_status} </div>";
		$Content .= '</div>';
		$Content .= '<div class="bike-data">';
		$Content .= "<div class=\"bike-desc\">{$oBike->bike_desc}</div>";
		$Content .= '</div>';
		$Content .= '</div>';
		$Content .= '</article>';
	}
	$Content .= '</section>';

	// MAINTENANCE SECTION
	$Content .= '<section id="maintenance-log" class="bike-section fade-in">';
	$Content .= '<h1 class="maintenance-log-title">MAINTENANCE LOG</h1>';
	$Content .= '<h2 id="maintenance-log-bike-name"></h2>';
	$Content .= '<div class="maintenance-header bike-section-header">';
	$Content .= '<div class="icons-wrapper">';
	$Content .= "<a href=\"javascript:void(0);\" onclick=\"showSection('bikes', 0, '')\">";
	$Content .= "<img src=\"{$bike_plugin_url}img/icon-bikes.png\" class=\"bike-icon\" />";
	$Content .= '<p class="icon-subtext">Bike List</p>';
	$Content .= '</a>';
	$Content .= "<a href=\"javascript:void(0);\" onclick=\"showSection('specs', {$oBike->id}, bikeName)\" id=\"maint-to-specs-link\">";
	$Content .= "<img src=\"{$bike_plugin_url}img/icon-specs.png\" class=\"bike-icon\" />";
	$Content .= '<p class="icon-subtext">Specs</p>';
	$Content .= '</a>';
	$Content .= '</div>';
	$Content .= '</div>';

	$Content .= '<div class="maintenance-entries bike-entries">';
	$Content .= '<header class="maintenance-entry-headers entry-headers">';
	$Content .= '<div class="col">DATE</div><div class="col">MAINT</div><div class="col">MILES</div>';
	$Content .= '</header>';
	// Purchase Date Row
	foreach ($aBikes as $oBike) {
		$dateArray = date_parse($oBike->purchase_date);
		$formattedDate = "{$dateArray['year']}-{$dateArray['month']}-{$dateArray['day']}";
		$Content .= "<div class=\"entry-record maintenance-entry row bike-{$oBike->id}\">";
		$Content .= "<div class=\"maint-date col\">{$formattedDate}</div>";
		$Content .= "<div class=\"maint-desc col\">Purchased</div>";
		$Content .= "<div class=\"bike-miles col\">0</div>";
		$Content .= '</div>';
	}
	foreach ($aMaintenance as $oMaintRecord) {
		$dateArray = date_parse($oMaintRecord->maintenance_date);
		$formattedDate = "{$dateArray['year']}-{$dateArray['month']}-{$dateArray['day']}";

		$Content .= "<div class=\"entry-record maintenance-entry row bike-{$oMaintRecord->bike_id}\">";
		$Content .= "<div class=\"maint-date col\">{$formattedDate}</div>";
		$Content .= "<div class=\"maint-desc col\">{$oMaintRecord->maintenance_desc}</div>";
		$Content .= "<div class=\"bike-miles col\">{$oMaintRecord->bike_miles}</div>";
		$Content .= '</div>';
	}
	$Content .= '<div class="no-records">NO RECORDS FOUND</div>';
	$Content .= '</div>';
	$Content .= '</section>';

	// SPECS SECTION
	$Content .= '<section id="specs-list" class="bike-section fade-in">';
	$Content .= '<h1 class="specs-list-title">SPECIFICATIONS</h1>';
	$Content .= '<h2 id="specs-list-bike-name"></h2>';
	$Content .= '<div class="specs-header bike-section-header">';
	$Content .= '<div class="icons-wrapper">';
	$Content .= "<a href=\"javascript:void(0);\" onclick=\"showSection('bikes', 0, '');\">";
	$Content .= "<img src=\"{$bike_plugin_url}img/icon-bikes.png\" class=\"bike-icon\" />";
	$Content .= '<p class="icon-subtext">Bike List</p>';
	$Content .= "<a href=\"javascript:void(0);\" onclick=\"showSection('specs', {$oBike->id}, bikeName);\"  id=\"specs-to-maint-link\">";
	$Content .= "<img src=\"{$bike_plugin_url}img/icon-maint.png\" class=\"bike-icon\" />";
	$Content .= '<p class="icon-subtext">Matinenance</p>';
	$Content .= '</a>';
	$Content .= '</div>';
	$Content .= '</div>';

	$Content .= '<div class="spec-entries bike-entries">';
	$Content .= '<header class="spec-entry-headers entry-headers">';
	$Content .= '<div class="col">NAME</div><div class="col">DESC</div>';
	$Content .= '</header>';
	//Serial Number Row
	foreach ($aBikes as $oBike) {
		$Content .= "<div class=\"entry-record spec-entry row bike-{$oBike->id}\">";
		$Content .= "<div class=\"spec-name col\">Serial Number</div>";
		$Content .= "<div class=\"spec-desc col\">{$oBike->serial_number}</div>";
		$Content .= '</div>';
	}
	foreach ($aSpecs as $oSpecRecord) {
		$Content .= "<div class=\"entry-record spec-entry row bike-{$oSpecRecord->bike_id}\">";
		$Content .= "<div class=\"spec-name col\">{$oSpecRecord->spec_name}</div>";
		$Content .= "<div class=\"spec-desc col\">{$oSpecRecord->spec_desc}</div>";
		$Content .= '</div>';
	}

	$Content .= '</div>';
	$Content .= '<div class="no-records">NO RECORDS FOUND</div>';
	$Content .= '</section>';


	$Content .= "<script type=\"text/javascript\" src=\"{$bike_plugin_url}js/common_functions.js\"></script>";

	return $Content;
}

//Add all the shortcodes
add_shortcode('sbm-bike-list', 'sbm_bike_list');

// During activation send any output to a file
add_action('activated_plugin', 'save_output_buffer_to_file');

register_activation_hook(__FILE__, 'activate_steves_bike_maintenance');
register_deactivation_hook(__FILE__, 'deactivate_steves_bike_maintenance');



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-steves-bike-maintenance.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_steves_bike_maintenance()
{

	$plugin = new Steves_Bike_Maintenance();
	$plugin->run();

}
/**
 * This function adds the custom styles for the plugin to the WP styling framework
 */
function steves_bike_maintenance_enqueue_styles()
{
	wp_enqueue_style('steves_bike_maintenance_style', plugin_dir_url(__FILE__) . 'css/steves-bike-maintenance.css');
}

// Add the custom styles
add_action('wp_enqueue_scripts', 'steves_bike_maintenance_enqueue_styles');

run_steves_bike_maintenance();
