=== BikePress ===
Contributors: OffTheKitchen
Tags: bicycle, maintenance, bikes
Requires at least: 4.7
Tested up to: 6.7
Stable tag: 5.0
License: GPLv2 or later

Track bicycles, specifications, and maintenance records, and display an interactive bike list via shortcode.

== Description ==

= BikePress = provides the ability to add bicycles, their specifications, and maintenance records. It also registers a shortcode
[bikepress-bike-list] which can be placed on pages and will render an interactive list of bikes.

Each bike can have details such as an image, serial number, make, model, status and purchase date. Specification records can be added for a bike to track
parts, standards or measurements for that bike. Likewise maintenance records can be added to track what work has been done on a bike for a given
date and mileage.

== Installation ==

This plugin is currently only installable via a zip file.

1. Place a copy of the wp-bikepress zip file in a directory
2. Via the WordPress plugins admin page, choose Add New Plugin
3. On the Add Plugin page, choose "Upload Plugin" and then "Choose File"
4. Browse to the zip file and select it
5. Choose "Install Now"
6. Once the plugin is installed, activate it. The activation will create the appropriate tables.

= Demo / test data =

By default, activation loads a small demo dataset so you can exercise the shortcode before admin CRUD is complete.

To disable demo data, add this to wp-config.php before activating:

`define( 'BIKEPRESS_LOAD_DEMO', false );`

To force demo data on (same as the current default when undefined):

`define( 'BIKEPRESS_LOAD_DEMO', true );`

If you previously used hardcoded wp_* table names on a site with a custom table prefix, uninstall the plugin and activate again so tables are recreated with the correct prefix. This release does not migrate old table names automatically.

== Frequently Asked Questions ==

= When are the tables deleted? =

The tables are deleted upon an uninstall of the plugin. Deactivation leaves tables and data in place.

== Screenshots ==

1. Screenshot of bike list rendered via shortcode.

== Changelog ==

= 5.0 =
* Prefixed custom table names via $wpdb->prefix
* Demo data gated by BIKEPRESS_LOAD_DEMO (default on when undefined)
* Simplified demo dataset
* Non-destructive deactivation; fuller uninstall cleanup
* Asset enqueue cleanup; shortcode output escaping

= 4.0 =
* BikePress branding, slug, shortcode, and code identifiers
* Plugin folder and main file: wp-bikepress
