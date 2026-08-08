# Version 1.3

**Status:** In progress  
**Base:** main (BikePress 1.2.0)

## Summary

Minor release line for admin UI formatting polish: BikePress menu branding, hub card layout, plugin site link behavior, and related copy fixes.

## Changes

### Features

_(none)_

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

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.3.zip`
- Unpacks to folder: `wp-bikepress/`
- Test: left menu BikePress; My Bikes 4 cards; Supporting Data cards with images/hover; no return header; Plugins Visit plugin site opens new tab
