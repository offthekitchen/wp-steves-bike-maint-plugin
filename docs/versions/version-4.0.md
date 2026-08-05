# Version 4.0

**Status:** In progress  
**Base:** main (after merging version-3)

## Summary

Major release that rebrands the plugin as BikePress and renames product identifiers, packaging, and the development skill—without changing database table names.

## Changes

### Features

- **bikepress-rename** (`version4.0-feature-bikepress-rename`): Rebrand to BikePress
  - What changed:
    - Display name, text domain, classes, functions, constants, and assets updated to BikePress / `bikepress` conventions
    - Plugin folder and main file renamed to `wp-bikepress` / `wp-bikepress.php`
    - Shortcode hard-cutover to `[bikepress-bike-list]`
    - Cursor skill moved to `.cursor/skills/bikepress-dev/` with updated paths and hyphenated branch naming
    - README history scrubbed; version set to 4.0.0
  - Why: Replace personal “Steve” product naming with a generic plugin identity while keeping existing DB tables intact

### Bugfixes

_(none in this change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v4.0.zip`
- Unpacks to folder: `wp-bikepress/`
- Update any pages still using the old shortcode to `[bikepress-bike-list]`
- Existing table names are unchanged (`wp_bikes`, etc.)
- Reopen the development folder `...\wp-bikepress` in Fork if the old path is still open
