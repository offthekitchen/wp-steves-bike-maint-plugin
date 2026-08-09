# Version 1.3

**Status:** Current  
**Base:** main (BikePress 1.2.0)

## Summary

Minor release **1.3.0** for admin UI formatting polish: BikePress menu branding, hub card layout and thumbnails, plugin site link behavior, and related copy fixes.

## Changes

### Features

- **release-1.3.0** (`version1.3-feature-release-1.3.0`): Cut plugin version 1.3.0
  - What changed: Plugin header and `BIKEPRESS_VERSION` set to `1.3.0`
  - Why: Ship the 1.3 line as a named WordPress plugin release

### Bugfixes

- **admin-ui-formatting** (`version1.3-bugfix-admin-ui-formatting`): Admin UI formatting polish
  - What was wrong:
    - Top menu still said “My Bikes” instead of BikePress
    - Hub cards used WordPress’s `.card` class and could lose thumbnails/hover layout
    - Supporting Data / My Bikes card rows were edge-pinned or collapsed to a narrow column
    - Shared “RETURN TO MY BIKES” header was redundant
    - Statuses card copy still said “labels”
    - Plugins “Visit plugin site” pointed at the wrong URL and opened in the same tab
  - What fixed it:
    - Top-level menu title **BikePress** (hub still My Bikes)
    - Hub cards renamed to `bikepress-hub-card` with centered flex layout (4 across on My Bikes desktop)
    - Removed return link from shared admin header
    - Statuses description: **Add and Maintain Bike Statuses**
    - `Plugin URI` → `https://www.offthekitchen.com/wordpress-development/` with `target="_blank"`

- **hub-card-images** (`version1.3-bugfix-hub-card-images`): Hub card thumbnail updates
  - What was wrong:
    - My Bikes Maintenance and Supporting Data cards used each other’s better-matched photos
    - Supporting Data Statuses and Import/Export cards reused the generic data thumbnail
  - What fixed it:
    - Swapped Maintenance ↔ Supporting Data thumbnails on My Bikes
    - Added compressed `manage-statuses-thumbnail.jpg` and `manage-import-export-thumbnail.jpg` for Supporting Data cards

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.3.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen should show **Version 1.3.0**
- Test: left menu BikePress; My Bikes 4 cards; Supporting Data cards with images/hover; no return header; Plugins Visit plugin site opens new tab
- Also test: My Bikes maint/data thumbs swapped; Supporting Data Statuses + Import/Export use new photos
