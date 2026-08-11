# Version 1.6

**Status:** In progress  
**Base:** main (BikePress 1.5.0)

## Summary

Minor release line for Manage Bikes field-limit fixes / shortcode list polish, and PDF bike reports.

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

- **bike-pdf-reports** (`version1.6-feature-bike-pdf-reports`): My Bikes Reports card and printable PDF
  - My Bikes hub **Reports** card (`admin/img/manage-reports-thumbnail.jpg`)
  - Reports also in the left WP admin submenu (visible, not hub-hidden)
  - Reports page: bike dropdown + **Create Bike Report (PDF)**
  - One PDF: summary page(s) then maintenance log page(s)
  - Summary: bike name, photo, Make/Model/Serial/Type/Status, description (no header) above Specs, then Specs (paginate if needed)
  - Maintenance: black header / white text; alternating row fills white / `#e6e6e6`; expanding rows; multi-page
  - Footer: date, Page N, “Created by BikePress”
  - Built-in PDF writer (`includes/class-bikepress-pdf.php`) — no Composer/Dompdf dependency

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.6.zip`
- Unpacks to folder: `wp-bikepress/`
- After upgrade: long descriptions save; model/serial up to 50 chars; Reports available from My Bikes and the admin submenu
- Test shortcode: SPEC header; mobile stacked details; desktop more/less on long descriptions
- Test Reports: pick a bike with photo, description, specs, and maintenance; download PDF and check summary + log layout, pagination, and footer
