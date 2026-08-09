# Version 1.2

**Status:** Superseded by 1.3.0  
**Base:** main (BikePress 1.1.0)

## Summary

Minor release **1.2.0** updating the BikePress admin footer to two columns with real About/Contact links, plus in-plugin Data & Privacy, Terms & Conditions, and About Me pages.

## Changes

### Features

- **admin-footer** (`version1.2-feature-admin-footer`): Admin footer and info pages
  - What changed:
    - Footer reduced to two columns: About BikePress and Contact
    - Links: Documentation and Buy Me a Coffee (new tab); Website (new tab); Email (`mailto:`); Data & Privacy, Terms & Conditions, About Me (in-plugin pages)
    - Footer shown on all BikePress admin screens
    - About Me page includes compressed about image and Buy Me a Coffee CTA; hub card thumbnails recompressed for smaller install zip
  - Why: Replace placeholder footer links with usable support/legal/contact content on every admin screen

- **release-1.2.0** (`version1.2-feature-release-1.2.0`): Cut plugin version 1.2.0
  - What changed: Plugin header and `BIKEPRESS_VERSION` set to `1.2.0`
  - Why: Ship the 1.2 line as a named WordPress plugin release

### Bugfixes

_(none as a separate change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.2.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen should show **Version 1.2.0**
- Test footer on hubs and CRUD pages; open Privacy / Terms / About Me; confirm external links open in a new tab where specified
