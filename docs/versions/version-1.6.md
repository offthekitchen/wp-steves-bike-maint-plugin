# Version 1.6

**Status:** In progress  
**Base:** main (BikePress 1.5.0)

## Summary

Minor release line for Manage Bikes field-limit fixes / shortcode list polish, PDF bike reports, shortcode image clarity, and admin navigation polish.

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

- **shortcode-bike-image-clarity** (`version1.6-bugfix-shortcode-bike-image-clarity`): Sharper bike photos on the public list
  - What was wrong:
    - Shortcode used WordPress `'thumbnail'` (often 150×150) while CSS displays at ~200×200, so images looked blurry
  - What fixed it:
    - Request `'medium'` from `wp_get_attachment_image_src` (same as Manage Bikes admin)

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

- **admin-nav-polish** (`version1.6-feature-admin-nav-polish`): Quicker jumps between related admin screens
  - Text **edit** links beside Type/Status on Manage Bikes (→ Manage Types / Statuses)
  - Text **edit** beside Bike dropdowns on Specs, Maintenance, and Reports (→ Edit that bike, or Manage Bikes list if none selected)
  - Edit Bike title is **Edit {bike name}** with secondary Specs / Maintenance / Bike Report buttons; removed Phase 2 related-links block
  - Specs/Maintenance filter: Filter button under the bike dropdown
  - Reports hub card uses `admin/img/admin-icon-reports.png`

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.6.zip`
- Unpacks to folder: `wp-bikepress/`
- After upgrade: long descriptions save; model/serial up to 50 chars; Reports available from My Bikes and the admin submenu
- Test shortcode: SPEC header; mobile stacked details; desktop more/less on long descriptions; bike photos look sharp at card size
- Test Reports: pick a bike with photo, description, specs, and maintenance; download PDF and check summary + log layout, pagination, and footer
- Test admin nav: Type/Status/Bike **edit** links; Edit Bike secondary buttons; Reports card icon; Specs/Maint Filter under dropdown
