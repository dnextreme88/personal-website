# On-demand release script for master

> **Outcome (2026-08-22).** Added `scripts/create-release.sh`, an on-demand
> release tool that Claude runs when asked. Added a **CLAUDE.md** convention
> that plans branch off `master` before work starts. Committed this plan and its
> README index entry. Verified with `scripts/create-release.sh --dry-run`
> (see **Verification**). A live tag/release is cut only when the owner asks and
> confirms, so no live release was published as part of executing this plan.

## Context

The repo had no release process. The owner wants to cut a release **on demand**:
when they ask, gather every pull request (PR) merged to `master` since the
previous release, create the next version tag, and publish a GitHub Release with
notes that list those PRs.

The version tag is `V{major}.{minor}`:

- `major` increases by 1 for each new **calendar day** on which a release is cut.
- `minor` is 0-based and counts the releases cut **on the same day**.

Worked examples (confirmed with the owner):

| Release event | Result |
| --- | --- |
| First release, 2026-08-22 | `V1.0` |
| Second release, same day 2026-08-22 | `V1.1` |
| Next release, 2026-08-25 (a later day) | `V2.0` |
| Next release, another later day | `V3.0` |

Decisions from the owner:

- **Trigger**: no GitHub Actions. The owner asks Claude to create a release, and
  Claude runs a committed script (`scripts/create-release.sh`).
- **Day boundary**: computed in **Asia/Manila (UTC+8)**.
- **Notes**: one line per merged PR, oldest merged first:
  `* {merge date}: {PR title} in #{PR number}` — for example
  `* 2026-08-08: Add dark mode toggle in #99999`.

## Approach

Add one committed bash script, `scripts/create-release.sh`. Claude runs it (via
Git Bash on Windows) when the owner asks for a release. It uses `git` and the
`gh` CLI; it needs `gh auth status` to be authenticated and push rights on the
repo.

The script has two modes:

- `scripts/create-release.sh --dry-run` — compute the next tag and print the
  notes, but change nothing. Claude runs this first and shows the owner.
- `scripts/create-release.sh` — after the owner confirms, create the annotated
  tag, push it, and publish the GitHub Release.

### The version rule

1. Find the highest existing tag matching `^V[0-9]+\.[0-9]+$` (version sort).
2. No tag yet -> `V1.0`.
3. A tag exists -> read the **tagger date** of that annotated tag in Manila
   time. That date is when the previous release was cut.
   - Today == that date: keep `major`, set `minor = minor + 1`.
   - Today  > that date: set `major = major + 1`, set `minor = 0`.

The tagger date (not the pointed commit's date) is used on purpose: releases are
decoupled from merge times, so "same day" must mean "another release was cut the
same day."

### The PR-collection rule

1. The range is `PREV_TAG..origin/master` (or all of `origin/master` for the
   first release).
2. List the **merge commits** in that range with `git log --merges --reverse`
   (oldest first). Each merge commit is one merged PR.
3. From each merge commit subject (`Merge pull request #N from ...`) take the PR
   number `N` and the merge date (committer date, Manila).
4. Fetch the PR title with `gh pr view N --json title`.
5. Emit `* {merge date}: {PR title} in #{N}`.

This relies on merge commits, which the repo uses today (for example
`Merge pull request #16 ...`). Squash- or rebase-merged PRs would not be caught
this way — see the caveat below.

## Files changed

### New: `scripts/create-release.sh`

The full script (see the file for the committed version):

```bash
#!/usr/bin/env bash
# Create the next V{major}.{minor} release from the PRs merged to master since
# the previous release. Run from the repo root.
#   scripts/create-release.sh --dry-run   # preview only
#   scripts/create-release.sh             # tag + push + publish release
set -euo pipefail

export TZ="Asia/Manila"

dry_run=0
[ "${1:-}" = "--dry-run" ] && dry_run=1

# 1. Sync master and tags; tag the current master tip.
git fetch --quiet origin master --tags
target="$(git rev-parse origin/master)"

# 2. Find the previous release tag and compute the next version.
latest="$(git tag --list 'V*' --sort=-v:refname \
  | grep -E '^V[0-9]+\.[0-9]+$' | head -n1 || true)"
today="$(date +%F)"

if [ -z "$latest" ]; then
  major=1; minor=0
  range="$target"                       # first release: whole history
else
  cur_major="${latest#V}"; cur_major="${cur_major%%.*}"
  cur_minor="${latest##*.}"
  # Release day = tagger date of the previous annotated tag.
  latest_day="$(git for-each-ref "refs/tags/$latest" \
    --format='%(taggerdate:format-local:%F)')"

  if [ "$today" = "$latest_day" ]; then
    major="$cur_major"; minor="$((cur_minor + 1))"
  else
    major="$((cur_major + 1))"; minor=0
  fi
  range="$latest..$target"
fi

new_tag="V${major}.${minor}"

# 3. Build the notes: one line per merged PR, oldest first.
notes="$(mktemp)"
found=0
while IFS='|' read -r mdate subject; do
  num="$(printf '%s' "$subject" | sed -nE 's/.*#([0-9]+).*/\1/p')"
  [ -z "$num" ] && continue
  title="$(gh pr view "$num" --json title -q .title 2>/dev/null || printf '%s' "$subject")"
  printf '* %s: %s in #%s\n' "$mdate" "$title" "$num" >> "$notes"
  found=1
done < <(git log --merges --reverse \
  --format='%cd|%s' --date=format-local:%F "$range")

if [ "$found" -eq 0 ]; then
  echo "No PRs merged since ${latest:-the beginning}. Nothing to release."
  rm -f "$notes"; exit 0
fi

echo "Next release: $new_tag  (previous: ${latest:-none})"
echo "----- notes -----"; cat "$notes"; echo "-----------------"

if [ "$dry_run" -eq 1 ]; then
  echo "(dry run) No tag or release was created."
  rm -f "$notes"; exit 0
fi

# 4. Create the annotated tag, push it, publish the release.
git tag -a "$new_tag" -m "Release $new_tag" "$target"
git push origin "$new_tag"
gh release create "$new_tag" \
  --title "$new_tag" \
  --notes-file "$notes" \
  --target "$target"

rm -f "$notes"
echo "Published $new_tag."
```

Design points:

- `TZ=Asia/Manila` makes `date`, `--date=format-local`, and
  `%(taggerdate:format-local)` all report the Manila calendar day.
- The script tags `origin/master` directly, so it is safe to run from any local
  branch and does not touch the working tree.
- Annotated tags (`git tag -a`) carry a tagger date, which the version rule
  reads next time. Do not create these release tags as lightweight tags.
- The PR title needs a `gh` API call; if it fails, the line falls back to the
  merge commit subject so a release is never blocked.
- Pushing a tag and publishing a release are public actions. Claude runs
  `--dry-run` first, shows the tag and notes, and publishes only after the owner
  confirms.

Caveats:

- **Merge strategy**: the script finds PRs via merge commits. If the repo ever
  switches to squash or rebase merges, switch the collection to
  `git log --no-merges` and parse the `(#N)` suffix instead.
- **Re-running**: if `new_tag` already exists, `git tag` fails safely (no
  duplicate). Delete the tag first if a re-cut is intended.
- **Empty range**: if no PR merged since the last release, the script reports it
  and exits without creating anything.

### Edit: `CLAUDE.md`

Added a **Miscellaneous** bullet after the **Plans** bullet:

> - **Executing a plan**: before starting the work in a plan, pull the latest
>   `master`, then create a new branch off it with a meaningful name per the
>   `type/YYYYMMDD-description` convention above. Do all plan work on that
>   branch, never directly on `master`.

### Docs: this plan

Committed as `docs/plans/20260822-on-demand-release-script.md` with a matching
entry in [README.md](README.md).

## Verification

1. **Preview only.** Run `scripts/create-release.sh --dry-run` on the real repo.
   Confirm:
   - the computed tag matches the day rule (new day -> major bump; same day ->
     minor bump; first ever -> `V1.0`)
   - the notes list every PR merged since the last release, oldest first, as
     `* {date}: {title} in #{number}`

2. **Same-day minor bump.** Create a first release, then run the script again the
   same day (after another PR merges) and confirm the minor increments
   (`V1.0` -> `V1.1`).

3. **New-day major bump.** On a later day, confirm the major increments and the
   minor resets (`V1.1` -> `V2.0`).

4. **Live publish.** Run without `--dry-run` on a throwaway PR set, then confirm
   the tag appears under **Tags** and the release under **Releases** with the
   expected notes.

5. **Clean up** any test tags and releases created during verification.
