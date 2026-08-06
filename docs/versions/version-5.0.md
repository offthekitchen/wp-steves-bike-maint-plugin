# Version 5.0

**Status:** Superseded by 1.0.0 (historical)  
**Base:** main (after BikePress 4.0 rename)

## Summary

Major release for lifecycle hardening plus admin CRUD Phases 1–3: Manage Bikes, Manage Specs, Manage Maintenance, and Supporting Data (statuses).

After this line was merged to `main`, the product was **renumbered to 1.0.0** as the first fully functional iteration. Keep this file for history; current packaging and plugin version use `docs/versions/version-1.0.md`.

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

- **bikes-crud** (`version5.0-feature-bikes-crud`): Phase 1 admin CRUD for bikes
  - What changed:
    - Manage Bikes list / add / edit / delete with nonce, capabilities, and sanitized fields
    - Delete confirms first, then removes related specs and maintenance for that bike
    - Media library image select/clear; status dropdown from statuses table
    - Phase 2 prep links on bike edit (specs/maintenance for `bike_id`)
    - My Bikes card hub look unchanged; manage page uses standard WP admin UI
  - Why: Maintain the bike fleet in admin without relying only on demo seed data

- **specs-maint-crud** (`version5.0-feature-specs-maint-crud`): Phase 2 admin CRUD for specs and maintenance
  - What changed:
    - Manage Specs and Manage Maintenance: list / add / edit / delete with nonces and capability checks
    - List all rows with bike name column; bike dropdown filter; honor `?bike_id=` from bike edit links
    - Spec fields: bike, name, description; maintenance fields: bike, date, description, miles
    - Delete confirms a single row only
    - Bike name in each list links to Manage Bikes edit for that bike
    - My Bikes card hub look unchanged
  - Why: Maintain specs and service history in admin alongside bikes

- **supporting-data-statuses** (`version5.0-feature-supporting-data-statuses`): Phase 3 Supporting Data hub + statuses CRUD
  - What changed:
    - Renamed Manage Data → Manage Supporting Data (`supporting-data-admin`); legacy `data-admin` redirects
    - Supporting Data hub matches My Bikes card style/layout; Statuses card uses copied `admin-icon-status.png`
    - Manage Statuses CRUD (list/add/edit/delete) reachable from the hub only (hidden from left submenu)
    - Delete status confirms, reassigns bikes to Unknown (creates Unknown if missing); Unknown cannot be deleted
  - Why: Maintain status labels safely without cluttering the WP submenu, with a hub ready for future supporting data

### Bugfixes

_(none as a separate change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v5.0.zip` (historical; current zip is `wp-bikepress-v1.0.zip`)
- Unpacks to folder: `wp-bikepress/`
- Zip is built with file entries only (no directory-only entries) so WordPress unpack on Windows succeeds
- Prefer uninstall → install → activate for a clean schema/demo seed
- Demo default on; disable with `define( 'BIKEPRESS_LOAD_DEMO', false );` in `wp-config.php`
- Custom-prefix sites that still have old hardcoded `wp_*` tables should reinstall rather than expect auto-migration
- Test bikes CRUD: My Bikes → Manage Bikes → add/edit/delete (confirm cascade)
- Test specs/maint CRUD: filter by bike, add/edit/delete; use bike-edit prep links; click bike name → bike edit
- Test supporting data: My Bikes → Supporting Data hub → Statuses; delete reassigns to Unknown; no Statuses item in left menu
