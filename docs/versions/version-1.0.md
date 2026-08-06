# Version 1.0

**Status:** Superseded by 1.1.0  
**Base:** main (after merging the former version 5.0 line)

## Summary

First fully functional BikePress product release, labeled **1.0.0** for WordPress and packaging. Functionally this is the completed 5.0 line (lifecycle hardening + admin CRUD Phases 1–3), renumbered so the Plugins screen and zip reflect a clean v1 starting point. Succeeded by **1.1.0** (import/export).

## Changes

### Features

- **housekeeping-v1-reset** (`housekeeping-v1-reset`): Post-5.0 housekeeping
  - What changed:
    - Plugin header and `BIKEPRESS_VERSION` set to `1.0.0`
    - Development skill: build a review zip during Implement, rebuild zip after version docs
    - Skill examples and notes updated for the 1.0 line; hub-only admin page guidance; SSL one-shot git note
  - Why: Treat the first complete iteration as version 1 and make sandbox testing easier earlier in the workflow

### Prior work (shipped as version 5.0, now part of 1.0.0)

See `docs/versions/version-5.0.md` for the detailed changelog of lifecycle hardening and CRUD Phases 1–3. That file is kept as historical record; **1.0.0** is the current product label.

### Bugfixes

_(none as a separate change)_

## Install / test notes

- Zip: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress-v1.0.zip`
- Unpacks to folder: `wp-bikepress/`
- Plugins screen should show **Version 1.0.0**
- Prefer uninstall → install → activate when replacing an older 5.0.0 install so WP picks up the new version string cleanly
