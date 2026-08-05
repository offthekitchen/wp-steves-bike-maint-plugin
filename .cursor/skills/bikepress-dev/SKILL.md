---
name: bikepress-dev
description: >-
  Gated incremental development workflow for the BikePress WordPress plugin.
  Use when the user requests a new feature, bugfix, change, enhancement, or
  any plugin development work; when analyzing requirements; planning
  implementation; creating version/feature/bugfix branches; building a plugin
  zip; updating version docs; committing; pushing; or opening a PR.
---

# BikePress Development Workflow

This skill defines the **only** process for features and bugfixes in this repo.
It is intentionally slow and approval-gated so the user can learn and stay in control.

**Read [learning-guide.md](learning-guide.md)** when explaining *why* a step exists.
**Use [templates.md](templates.md)** for Analysis and Plan output shapes.

## Absolute rules

1. **Never skip a hard stop.** Stop and wait for explicit user approval before the next phase.
2. **Never commit or push** until the user explicitly approves that step.
3. **Never start Design/Plan, branching, or implementation** until the prior phase is approved.
4. If the user does not say whether the request is a **feature** or **bugfix**, ask before continuing Analysis.
5. Prefer teaching briefly at each phase (what you are doing and why) without dumping lectures.
6. Do not merge PRs unless the user later asks you to. Default: create the PR; user merges outside GitHub for now.

## Progress checklist

Copy and keep updated in your replies during a change:

```
Workflow progress:
- [ ] Analysis (awaiting approval)
- [ ] Design/Plan (awaiting approval)
- [ ] Create Branch (awaiting ready-to-implement approval)
- [ ] Implement code (awaiting review/test approval)
- [ ] Version docs + zip (awaiting approval)
- [ ] Commit + push + PR
```

---

## Phase 1 — Analysis

### Steps

1. The user asks for a new feature or bugfix. If they do not identify whether this is a bugfix or a feature, ask them.
2. Then ask any other questions needed to analyze.
3. Once you have the info you need, clearly state the **business requirements**, **intended purpose**, and **overall approach** for the user to review.
4. **Hard stop here until the user approves the analysis.** Rework the analysis if needed until they approve. **Do not proceed without approval.**

### Agent notes

- Use the Analysis template in [templates.md](templates.md).
- Ask only questions that unblock requirements (scope, expected behavior, out-of-scope, acceptance checks).
- Do not invent UI/UX or data model details as decided facts; mark assumptions and confirm them.
- End the reply with a clear prompt: e.g. “Please approve this analysis, or tell me what to change.”

---

## Phase 2 — Design/Plan

### Steps

1. Once approved, devise a more detailed plan for the user to review.
2. **Hard stop here until the user approves the plan.** Rework the plan if needed until they approve. **Do not proceed without approval.**

### Agent notes

- Use the Design/Plan template in [templates.md](templates.md).
- Include: files likely touched, data/DB impact, admin vs public/shortcode impact, test steps, and risks.
- Keep the plan implementable but not a full code dump.
- End with: “Please approve this plan, or tell me what to change.”

---

## Phase 3 — Create Branch

### Steps

1. If the latest version branch (e.g. version 3 or version 4.3) or any of its feature or bugfix sub-branches are not merged, that version is in progress. If it is in progress, ask the user if they want to include this request in that version.
2. If they say yes, then just create the new feature or bugfix branch off of the latest version branch (e.g. `versionx.x-bugfix-[name]` or `versionx.x-feature-[name]` where `x.x` = in progress version number and `[name]` = meaningful name).
3. If they say no, then ask them if they want to merge the in progress version, and if so, walk them through getting all the changes in that version merged all the way back up to main.
4. If they are not including this change in an in progress version, ask them if this is to be included in a minor or major release.
5. Once they indicate whether this is a new major or minor version, increment either the major or minor number and create a new version branch off of the main branch (e.g. `versionx.x` where `x.x` = new version number).
6. Change to this new version branch and branch again off of it for the change (e.g. `versionx.x-bugfix-[name]` or `versionx.x-feature-[name]` where `x.x` = new version number and `[name]` = meaningful name). For example, if the latest release was version 4.2 and they indicated that this will be in a minor release create a `version4.3-bugfix-[name]` or `version4.3-feature-[name]` branch. If they indicated that this is a major release, create a `version5.0-bugfix-[name]` or `version5.0-feature-[name]` branch.
7. Check out the newly created branch.

### Agent notes — detecting “in progress”

Run git (see Git notes below) and determine:

- **Version branches**: names like `version1`, `version-2`, `version-3`, `version4.0`, `version4.3` (normalize when comparing).
- **Latest version branch**: highest major.minor (treat `version-3` / `version3` as `3.0` if no minor is present).
- **In progress**: latest version branch (or any `versionX.Y-feature-*` / `versionX.Y-bugfix-*` child) is **not** fully merged into `main`.

Also check for uncommitted local changes before branching. If the working tree is dirty, stop and ask the user how to handle it (stash, commit on current branch, discard, or carry into the new branch). Do not destroy work.

### Branch naming

| Kind | Pattern | Example |
|------|---------|---------|
| Version line | `version{major}.{minor}` preferred; accept existing `version-3` style while migrating | `version4.3` |
| Feature | `version{major}.{minor}-feature-{short-name}` | `version4.3-feature-bike-search` |
| Bugfix | `version{major}.{minor}-bugfix-{short-name}` | `version4.3-bugfix-shortcode-filter` |

**Git note:** Do **not** use `version4.3/feature-name` (slash). Git cannot have both a branch `version4.3` and `version4.3/feature-name`. Use a hyphen after the version number instead.

Use lowercase kebab-case for `[name]`. Keep names short and meaningful.

### New major vs minor (when not joining in-progress version)

- **Minor**: increment minor (`4.2` → `4.3`).
- **Major**: increment major, set minor to `0` (`4.2` → `5.0`).
- Create `versionX.Y` from `main`, then create and check out `versionX.Y-feature-...` or `versionX.Y-bugfix-...` from that version branch.

---

## Phase 4 — Implement

### Steps

1. Tell the user the branch you created and ask if they are ready to proceed.
2. **Hard stop.** Do not proceed until they approved.
3. Once approved, begin to implement the proposed changes. If necessary, rework the changes until they approve them.
4. Allow them to review and test the changes before committing and pushing. **Do not commit or push any code.**
5. **Hard stop.** Do not proceed until they approved.
6. Once they approve the changes, either create a new version documentation file or update the existing one with the feature or bugfix explained.
7. Create a zip file of the plugin so they can install it on a WP site and test.
8. **Hard stop.** Do not proceed until they approved.
9. Once they approve, commit and push the changes.
10. Create a PR for the change.
11. For the time being they will approve the PR and merge the change outside of Github. They may choose to do this within Cursor later.

### Agent notes — implementation

- Follow the approved plan; if reality forces a material deviation, stop and re-approve the change in approach.
- Match existing plugin style; prefer small, teachable diffs.
- After code is ready for review, summarize what changed and how to test locally. Remind them: no commit/push yet.
- WordPress plugin context for this repo:
  - Main bootstrap: `wp-bikepress.php`
  - Activation/DB/test data: `includes/class-bikepress-activator.php`, `includes/test-data.php`
  - Uninstall: `uninstall.php`
  - Admin UI: `admin/`
  - Public/shortcode: `public/` (`[bikepress-bike-list]`)
  - Do not break activation, deactivation, or uninstall behavior unless the approved plan says so.

### Version documentation

- Location: `docs/versions/`
- File: `version-{major}.{minor}.md` (example: `docs/versions/version-4.3.md`)
- If the file exists, append an entry for this feature/bugfix.
- If it does not exist, create it for the version line.
- Use the Version Doc template in [templates.md](templates.md).

### Plugin zip for install testing

- Parent project folder (one level above this git repo): `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\`
- Preferred zip output: that parent folder, as `wp-bikepress-v{major}.{minor}.zip`.
- Zip **contents** must unpack to a single folder named `wp-bikepress/` (WordPress expects a plugin folder).
- Exclude: `.git/`, `.cursor/`, `dist/`, `node_modules/`; never include secrets.
- On Windows PowerShell, prefer Compress-Archive or a small scripted zip that preserves the plugin root folder name.
- Tell the user the full path to the zip and remind them they can upload it via WP Admin → Plugins → Add New → Upload Plugin (into the sandbox deploy path, not this development tree).

### Commit, push, PR (only after zip/docs approval)

- Commit only when the user approves step 9.
- Follow the user’s git commit rules (HEREDOC-style message, no force push, no config changes, no secrets).
- Push the feature/bugfix branch to `origin`.
- Open a PR with `gh pr create`:
  - **Base**: the version branch (e.g. `version4.0`), not necessarily `main`, unless the user asks otherwise.
  - **Head**: the feature/bugfix branch.
  - Summary should reference the approved requirements and test notes.
- After creating the PR, stop. Do not merge unless asked.

---

## Repo layout reminder

```
C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\     ← project folder (zips, notes, assets)
└── wp-bikepress\                                         ← THIS git repo / plugin source (workspace root)
```

Do **not** edit the deployed copy under `wp-sandbox\wp-content\plugins\...` unless the user explicitly asks. Development happens here; testing uses the zip on the sandbox site.

## Git notes for this machine/repo

- Workspace / git root: `C:\Data\Web Sites\plugins\wp-steves-bike-maint-plugin\wp-bikepress`
- Remote: `origin` → `https://github.com/offthekitchen/wp-steves-bike-maint-plugin.git` (GitHub repo name unchanged for now)
- Default integration branch: `main`
- This environment may hit `fatal: detected dubious ownership`. Prefer one-shot overrides:

```bash
git -c safe.directory="C:/Data/Web Sites/plugins/wp-steves-bike-maint-plugin/wp-bikepress" <command>
```

- **Do not** run `git config` to fix safe.directory unless the user explicitly asks.
- Existing historical branches may use `version1`, `version-2`, `version-3`. Treat those as version lines when detecting “in progress.” Prefer `version{major}.{minor}` for new version lines going forward.

---

## Phase transition phrases (use these)

| After completing | Say something like |
|------------------|--------------------|
| Analysis | “Hard stop — please approve this analysis (or request changes).” |
| Design/Plan | “Hard stop — please approve this plan (or request changes).” |
| Branch created | “Created and checked out `{branch}`. Ready to implement? Hard stop until you approve.” |
| Code ready | “Please review/test. I have not committed or pushed. Hard stop until you approve.” |
| Docs + zip ready | “Updated version docs and built `{zip path}`. Hard stop until you approve commit/push/PR.” |
| PR opened | “PR is ready: {url}. Merge when you are ready (outside GitHub for now).” |
