# Templates — Analysis, Plan, Version Docs

Use these shapes when producing review artifacts. Keep language concrete and short.

---

## Analysis template

```markdown
## Analysis

**Change type:** Feature | Bugfix
**Working title:** …

### Business requirements
- …
- …

### Intended purpose
One short paragraph: why this change exists and who it helps.

### Overall approach
- High-level approach (not file-level detail yet)
- In scope: …
- Out of scope: …
- Assumptions to confirm: …

### Acceptance checks (draft)
- [ ] …
- [ ] …

### Open questions
- … (omit this section if none)

---
Hard stop — please approve this analysis (or tell me what to change).
```

---

## Design/Plan template

```markdown
## Design / Plan

**Change type:** Feature | Bugfix
**Based on approved analysis:** (one-line summary)

### Implementation plan
1. …
2. …
3. …

### Files likely touched
- `path/to/file` — why
- …

### Data / activation impact
- None | Tables/columns/options/test data: …

### Surfaces affected
- [ ] Admin
- [ ] Public / shortcode
- [ ] Activation / deactivation / uninstall
- [ ] Assets (CSS/JS/images)

### Test plan (for your sandbox)
1. …
2. …
3. Install/test via the **review zip** built during Implement (rebuild again after docs)

### Risks / rollback notes
- …

---
Hard stop — please approve this plan (or tell me what to change).
```

---

## Version documentation template

Create or update `docs/versions/version-{major}.{minor}.md`:

```markdown
# Version {major}.{minor}

**Status:** In progress | Released
**Base:** main (or note prior version)

## Summary
Short description of this release line.

## Changes

### Features
- **{short-name}** ({branch}): …
  - What changed:
  - Why:

### Bugfixes
- **{short-name}** ({branch}): …
  - What was wrong:
  - What fixed it:

## Install / test notes
- Zip: parent folder `wp-bikepress-v{major}.{minor}.zip`
- Special setup steps (if any):
```

When appending to an existing file, add under the correct Features or Bugfixes heading; do not rewrite unrelated entries.

---

## PR body template

```markdown
## Summary
- Change type: Feature | Bugfix
- Version line: versionX.Y
- What / why (2–4 bullets)

## Test plan
- [ ] …
- [ ] Installed and smoked-tested via `wp-bikepress-vX.Y.zip`

## Notes
- Merging will be handled outside GitHub for now unless requested otherwise.
```
