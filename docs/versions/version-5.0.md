# Version 5.0

**Status:** In progress  
**Base:** main (after BikePress 4.0 rename)

## Summary

Major release focused on WordPress lifecycle hardening: prefixed table names, gated/simplified demo data, non-destructive deactivation, fuller uninstall, asset enqueue cleanup, and shortcode escaping—without expanding incomplete admin CRUD.

## Changes

### Features

- **lifecycle-hardening** (`version5.0-feature-lifecycle-hardening`): Plugin lifecycle and public path improvements
  - What changed:
    - Custom tables use `$wpdb->prefix` consistently (activate, runtime constants, uninstall)
    - Demo data simplified; loads when `BIKEPRESS_LOAD_DEMO` is undefined or `true` (set `false` in `wp-config.php` to skip)
    - Deactivation no longer deletes `bikepress_db_version` or data
    - Uninstall drops prefixed tables and cleans BikePress options
    - Admin/public assets enqueued on hooks and scoped; removed file-scope enqueues and shortcode inline script tag
    - Shortcode output escaped; plugin version set to 5.0.0
  - Why: Safer install/activate/deactivate/uninstall behavior and a portable demo dataset while admin CRUD is still incomplete

### Bugfixes

_(none as a separate change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v5.0.zip`
- Unpacks to folder: `wp-bikepress/`
- Zip is built with file entries only (no directory-only entries) so WordPress unpack on Windows succeeds
- Prefer uninstall → install → activate for a clean schema/demo seed
- Demo default on; disable with `define( 'BIKEPRESS_LOAD_DEMO', false );` in `wp-config.php`
- Custom-prefix sites that still have old hardcoded `wp_*` tables should reinstall rather than expect auto-migration
