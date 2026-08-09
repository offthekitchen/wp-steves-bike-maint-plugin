# Version 1.4

**Status:** In progress  
**Base:** main (BikePress 1.3.0)

## Summary

Minor release line after 1.3.0: Manage Bikes media picker fix, plus optional demo data (off by default) with Supporting Data card, first-run notice, and shortcode empty state.

## Changes

### Features

- **optional-demo-data** (`version1.4-feature-optional-demo-data`): Optional demo data import
  - What changed:
    - Activation no longer loads demo data by default (`BIKEPRESS_LOAD_DEMO` must be true to seed on activate)
    - First-run admin notice on BikePress screens: Import demo data / No thanks
    - Supporting Data hub card **Import Demo Data** (shared import action; blocked if bikes/statuses already exist)
    - `[bikepress-bike-list]` empty state encourages adding bikes or importing demo data
  - Why: Let real installs start clean while still offering sample data on demand

### Bugfixes

- **select-image-media** (`version1.4-bugfix-select-image-media`): Restore Manage Bikes Select image media library
  - What was wrong: Select image no longer opened the WordPress media library
  - What fixed it: Enqueue `wp_enqueue_media()` and `js/admin.js` on Manage Bikes via hook or `page=bikes-admin`, with `media-editor` / `media-views` script dependencies

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.4.zip`
- Unpacks to folder: `wp-bikepress/`
- Prefer uninstall → install → activate to verify empty start + first-run notice
- Test: Select image media picker; demo notice/card; shortcode empty message; import blocked when data exists
