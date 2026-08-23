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
