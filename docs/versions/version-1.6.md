# Version 1.6

**Status:** In progress  
**Base:** main (BikePress 1.5.0)

## Summary

Minor release line for Manage Bikes field-limit fixes / shortcode list polish, and (planned) PDF bike reports.

## Changes

### Bugfixes

- **bike-field-limits** (`version1.6-bugfix-bike-field-limits`): Widen bike fields and clarify save errors
  - What was wrong:
    - Long descriptions failed to save (`bike_desc` was `varchar(255)`) with a generic error
    - Model and serial were too short (`varchar(15)` / `varchar(30)`)
  - What fixed it:
    - `bike_desc` → `TEXT`; model and serial → `varchar(50)`; `bikepress_db_version` → **1.2**
    - Server-side length checks and clearer notices; form `maxlength` hints
    - Specs list header **NAME** → **SPEC**
    - Phone: Make/Model/Type/Status stack in a column
    - Desktop: descriptions longer than 64 characters use **more...** / **less...**; card grows, photo size unchanged

### Features

- **bike-pdf-reports** (`version1.6-feature-bike-pdf-reports`): *(planned)* My Bikes Reports card and printable PDF (summary/specs + maintenance log)

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.6.zip`
- Unpacks to folder: `wp-bikepress/`
- After upgrade: long descriptions save; model/serial up to 50 chars
- Test shortcode: SPEC header; mobile stacked details; desktop more/less on long descriptions
