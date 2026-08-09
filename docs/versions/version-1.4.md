# Version 1.4

**Status:** In progress  
**Base:** main (BikePress 1.3.0)

## Summary

Minor release line for admin bugfixes after 1.3.0, starting with restoring the Manage Bikes media library image picker.

## Changes

### Features

_(none)_

### Bugfixes

- **select-image-media** (`version1.4-bugfix-select-image-media`): Restore Manage Bikes Select image media library
  - What was wrong: Select image no longer opened the WordPress media library
  - What fixed it: Enqueue `wp_enqueue_media()` and `js/admin.js` on Manage Bikes via hook or `page=bikes-admin`, with `media-editor` / `media-views` script dependencies

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.4.zip`
- Unpacks to folder: `wp-bikepress/`
- Test: Manage Bikes → Select image opens media library; choose image updates preview; Clear image resets to default
