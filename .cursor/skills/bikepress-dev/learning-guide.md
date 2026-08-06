# Learning Guide — BikePress Development Workflow

This guide explains **why** the workflow exists and what each phase teaches you. The agent follows [SKILL.md](SKILL.md); this file is for you.

## Development vs deployed copies

You keep related locations:

| Role | Path |
|------|------|
| **Development (this repo)** | `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress` |
| **Project folder** (zips, notes, design assets) | `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin` |
| **Deployed sandbox plugin** | under `wp-sandbox\wp-content\plugins\...` |

Edit and commit in **development**. Install/test via zip on the **sandbox** site. Avoid editing the deployed copy as if it were source control.

## Why a gated workflow?

WordPress plugins touch a live site: database tables, admin screens, and front-end shortcodes. Small mistakes are easier to catch when you separate:

1. **What** you want (Analysis)
2. **How** you’ll build it (Design/Plan)
3. **Where** the work lives in git (Create Branch)
4. **Doing** the work and proving it (Implement → review zip → docs → zip rebuild → commit/PR)

Hard stops keep Cursor from racing ahead so you can learn each decision.

## Mental model of this plugin

Rough layout:

```
wp-bikepress.php   → plugin header, version, hooks bootstrap
includes/          → core logic, activation, models
admin/             → WP Admin menus and pages
public/            → front-end / shortcode UI ([bikepress-bike-list])
uninstall.php      → cleanup when the plugin is deleted
```

Typical change categories:

| Kind | Examples |
|------|----------|
| Feature | New admin screen, new field, new shortcode behavior |
| Bugfix | Wrong query, broken UI, activation failure |

If you are unsure which one you are asking for, say so — the agent will ask.

## Phase-by-phase learning notes

### Analysis

**Goal:** Agree on the problem and success criteria before touching code.

You should leave Analysis knowing:

- Is this a feature or a bugfix?
- Who benefits (you in admin, site visitors via shortcode, both)?
- What “done” looks like in plain language

**Tip:** Good requirements sound like outcomes (“Admin can filter bikes by status”), not implementations (“Add a jQuery change handler”).

### Design/Plan

**Goal:** Turn approved requirements into a concrete, reviewable plan.

You should leave Design knowing:

- Which files will change
- Whether DB tables or activation/test data are affected
- How you will test on your WordPress sandbox

**Tip:** If the plan proposes a DB change, think about existing installs: activation runs once; you may need an upgrade path later. For early development it is often OK to reinstall, but call that out consciously.

### Create Branch

**Goal:** Isolate work so `main` stays stable and versions stay understandable.

Branch idea:

```
main
 └── version1.0                              ← version line for a release
      ├── version1.0-feature-bike-search     ← one change
      └── version1.0-bugfix-status-filter   ← another change
```

**Git limitation:** You cannot name a feature branch `version1.0/feature-…` while `version1.0` also exists as a branch. Use a hyphen: `version1.0-feature-…`.

**In progress version:** If a version branch still has unmerged work, new work either joins that version or waits for a new major/minor line. That choice is yours; the agent must ask.

**Major vs minor (simple rule of thumb):**

- **Minor** — additive or small fixes; existing behavior mostly stays compatible
- **Major** — breaking changes, big redesigns, or a clear “new generation” of the plugin

Exact semver purity matters less right now than **you choosing intentionally**.

### Implement

**Goal:** Change only what the plan approved, then prove it with a zip install **before** you approve the code.

Flow of control stays with you:

1. Approve starting work on the branch
2. Review/test code using the **review zip** from Implement (no commit yet)
3. Approve docs + **rebuilt** zip (includes latest version docs)
4. Approve commit, push, and PR

**Why zip during Implement?** You can install and click through on the sandbox before approving the code, instead of waiting for the docs step.

**Why rebuild the zip after docs?** The final artifact should match what you document for that version line.

**Why PR even if you merge outside GitHub?** The PR records intent, diff, and test notes. Later you can merge in GitHub or Cursor with the same history.

## Version documentation

Each version line gets a markdown file under `docs/versions/`, for example:

`docs/versions/version-1.0.md`

That file is the human changelog for the release line: features and bugfixes in plain language. Update it when code is approved, before the final commit.

## Suggested local test habit

1. Build the zip into the parent project folder
2. Upload/install on the sandbox WP site (or replace the deployed plugin folder carefully)
3. Exercise activation only when the change needs fresh tables/data
4. Test the shortcode page and any admin screens touched
5. If you uninstall to reset DB, remember activation will reload test data (current plugin behavior)

## What “approval” means

Any of these is enough when you mean yes:

- “Approved”
- “Looks good”
- “Proceed”
- “LGTM”

If you want changes, say what to change. The agent should rework that phase and hard-stop again.

## Optional next learning topics (later)

Not required for the skill, but natural follow-ons as the plugin grows:

- Semantic versioning vs WordPress “Stable tag” in `README.txt`
- DB upgrade routines vs drop/recreate on activate
- Separating test data from production activation
- Automated checks (PHPCS, simple PHPUnit)

Ask for these when you want to tackle them as their own change requests.
