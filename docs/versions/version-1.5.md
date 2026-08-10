# Version 1.5

**Status:** Current  
**Base:** main (BikePress 1.4.0)

## Summary

Minor release **1.5.0**: bike types as supporting data (schema 1.1), Manage Types admin, type on bikes/shortcode/import-export.

## Changes

### Features

- **bike-types** (`version1.5-feature-bike-types`): Bike types as supporting data
  - What changed:
    - New `bike_type` table and `bike_type_id` on bikes; `bikepress_db_version` bumped to **1.1** (auto-upgrade on load)
    - Plugin data seeds Road, Mountain, Gravel, Commuter, eBike on activate (insert-if-missing); Unknown created on demand
    - Supporting Data hub card **Manage Types** with CRUD (Unknown protected; delete reassigns bikes to Unknown only when needed)
    - Manage Bikes type dropdown and Type column; demo bikes map Mountain / Commuter / Gravel
    - Import/export includes `types`, `bike_type_id`, and `plugin_version`; older **1.0** exports import into 1.1 with Unknown type
    - `[bikepress-bike-list]` shows **TYPE** immediately before **STATUS**
  - Why: Classify bikes by type the same way statuses work, without tying types to demo data

- **release-1.5.0** (`version1.5-feature-release-1.5.0`): Cut plugin version 1.5.0
  - What changed: Plugin header and `BIKEPRESS_VERSION` set to `1.5.0`
  - Why: Ship the 1.5 line as a named WordPress plugin release

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.5.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen should show **Version 1.5.0**
- After activate/upgrade: core types present; existing bikes without a type get Unknown
- Test: Manage Types CRUD; set type on a bike; shortcode TYPE line; export JSON; import a 1.0 export if available
