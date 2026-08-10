# Version 1.4

**Status:** Superseded by 1.5.0  
**Base:** main (BikePress 1.3.0)

## Summary

Minor release **1.4.0**: Manage Bikes media picker fix; optional demo data (bikes/specs/maint); core statuses seeded as plugin data on activate.

## Changes

### Features

- **optional-demo-data** (`version1.4-feature-optional-demo-data`): Optional demo data import
  - What changed:
    - Activation no longer loads demo data by default (`BIKEPRESS_LOAD_DEMO` must be true to seed on activate)
    - First-run admin notice on BikePress screens: Import demo data / No thanks
    - Supporting Data hub card **Import Demo Data** (shared import action; blocked if bikes already exist)
    - `[bikepress-bike-list]` empty state encourages adding bikes or importing demo data
  - Why: Let real installs start clean while still offering sample data on demand

- **release-1.4.0** (`version1.4-feature-release-1.4.0`): Cut plugin version 1.4.0
  - What changed: Plugin header and `BIKEPRESS_VERSION` set to `1.4.0`
  - Why: Ship the 1.4 line as a named WordPress plugin release

### Bugfixes

- **select-image-media** (`version1.4-bugfix-select-image-media`): Restore Manage Bikes Select image media library
  - What was wrong: Select image no longer opened the WordPress media library
  - What fixed it: Enqueue `wp_enqueue_media()` and `js/admin.js` on Manage Bikes via hook or `page=bikes-admin`, with `media-editor` / `media-views` script dependencies

- **plugin-data-statuses** (`version1.4-bugfix-plugin-data-statuses`): Core statuses as plugin data
  - What was wrong: Statuses were tied to demo seed; delete always created Unknown; demo import recreated core statuses
  - What fixed it:
    - `BikePress_Plugin_Data::ensure_core_statuses()` seeds Active / Retired / Building on activate (insert-if-missing)
    - Demo data is bikes/specs/maintenance only; missing status names resolve to Unknown
    - Status delete creates Unknown only when bikes use that status

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.4.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen should show **Version 1.4.0**
- Prefer uninstall → install → activate: 3 core statuses, no bikes; first-run notice
- Test: delete unused status (no Unknown); demo import with deleted Active → bikes get Unknown; Select image; shortcode empty message
