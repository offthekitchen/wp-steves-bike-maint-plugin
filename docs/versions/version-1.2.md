# Version 1.2

**Status:** In progress  
**Base:** main (BikePress 1.1.0)

## Summary

Minor release updating the BikePress admin footer to two columns with real About/Contact links, plus in-plugin Data & Privacy, Terms & Conditions, and About Me pages. Plugin display version remains **1.1.0** until this line is cut as a release.

## Changes

### Features

- **admin-footer** (`version1.2-feature-admin-footer`): Admin footer and info pages
  - What changed:
    - Footer reduced to two columns: About BikePress and Contact
    - Links: Documentation and Buy Me a Coffee (new tab); Website (new tab); Email (`mailto:`); Data & Privacy, Terms & Conditions, About Me (in-plugin pages)
    - Footer shown on all BikePress admin screens
    - About Me page includes compressed about image; hub card thumbnails recompressed for smaller install zip
  - Why: Replace placeholder footer links with usable support/legal/contact content on every admin screen

### Bugfixes

_(none as a separate change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.2.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen still shows **1.1.0** until the 1.2 release is cut
- Test footer on hubs and CRUD pages; open Privacy / Terms / About Me; confirm external links open in a new tab where specified
