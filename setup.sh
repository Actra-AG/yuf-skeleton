#!/usr/bin/env bash
# Creates a new project from the yuf skeleton with DDEV, initializes Git and makes the first commit.
# Usage: bash <(curl -fsSL https://raw.githubusercontent.com/Actra-AG/yuf-skeleton/main/setup.sh)
# Without arguments it asks; for scripts: setup.sh <project-name> <company> [license]
# Needs DDEV and Git, no local PHP or Composer.

set -euo pipefail

fail() {
  echo "Error: $1" >&2
  exit 1
}

command -v ddev > /dev/null || fail "DDEV is not installed, see https://ddev.com/get-started/"
command -v git > /dev/null || fail "Git is not installed."
git config user.name > /dev/null && git config user.email > /dev/null \
  || fail "Set your Git identity first: git config --global user.name/user.email"

# Reads from the terminal, also when the script itself comes from stdin
ask() {
  local answer
  read -r -p "$1" answer < /dev/tty
  echo "$answer"
}

name="${1:-$(ask "Project name (lowercase letters, digits and hyphens, the site will be <name>.ddev.site): ")}"
[[ "$name" =~ ^[a-z0-9]([a-z0-9-]*[a-z0-9])?$ ]] || fail "Invalid project name '$name'."
[[ ! -e "$name" ]] || fail "'$name' already exists in $(pwd)."

company="${2:-$(ask "Copyright holder (your company or name): ")}"
# The value goes into PHP comments and a PHP string: no quotes, $, backslashes or comment ends
forbidden='["$`\\]|\*/'
[[ -n "$company" && ! "$company" =~ $forbidden ]] || fail "Invalid copyright holder '$company'."

if [[ $# -ge 2 ]]; then
  license="${3:-MIT}"
else
  license="$(ask "License (SPDX identifier, e.g. MIT or proprietary) [MIT]: ")"
  license="${license:-MIT}"
fi
[[ "$license" =~ ^[A-Za-z0-9.+-]+$ ]] || fail "Invalid license '$license'."

mkdir "$name"
cd "$name"
ddev config --project-type=php --php-version=8.5
ddev start
ddev composer create-project actra/yuf-skeleton
# Apply the DDEV configuration of the skeleton (Apache, document root public/)
ddev restart

# Fill in the file header and the license; values are passed as environment variables, not as code
export YUF_COMPANY="$company" YUF_LICENSE="$license"
grep --recursive --files-with-matches --fixed-strings --exclude-dir=vendor '[Your company or name]' . \
  | xargs perl -pi -e 's/\Q[Your company or name]\E/$ENV{YUF_COMPANY}/g;' \
    -e 's/\Q[License of your project]\E/$ENV{YUF_LICENSE}/g'
perl -pi -e 's/^(\s*"license": )"[^"]*"/$1"$ENV{YUF_LICENSE}"/' composer.json
# The file header is done: remove its TODO from AGENTS.md
perl -0pi -e 's/- TODO: adapt the copyright and the license.*?Remove this bullet once done\.\n//s' AGENTS.md
ddev composer cs > /dev/null || fail "The code style check failed after filling in the file header."

git init --quiet --initial-branch=main
git add --all
git commit --quiet --message="chore: create project from yuf skeleton"

ddev launch
echo
echo "Done: $(pwd), https://$name.ddev.site/"
echo "Next steps: see \"After creating the project\" in README.md."
