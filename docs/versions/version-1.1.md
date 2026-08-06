# Version 1.1

**Status:** In progress  
**Base:** main (BikePress 1.0.0)

## Summary

Minor release adding JSON import/export of all BikePress data from the Supporting Data hub, so data can be backed up and restored across reinstalls. Plugin display version remains **1.0.0** until this line is cut as a release.

## Changes

### Features

- **import-export** (`version1.1-feature-import-export`): Import / Export Data
  - What changed:
    - New Import/Export card on the Supporting Data hub (hub-only admin page)
    - Export downloads JSON of statuses, bikes, specs, and maintenance, tagged with `bikepress_db_version`
    - Import upserts by row `id` (update if exists, insert otherwise); clears `bike_image_id` when the attachment is missing
    - Import blocked when file `db_version` does not match the site
  - Why: Preserve fleet data across uninstall/reinstall and provide a portable backup format for future schema-aware tooling

### Bugfixes

_(none as a separate change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.1.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen still shows **1.0.0** until the 1.1 release is cut
- Test: Supporting Data → Import/Export → export, re-import, mismatch db_version blocked
- Hub-only: Import/Export not listed in the left submenu
