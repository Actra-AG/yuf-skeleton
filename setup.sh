#!/usr/bin/env bash
# Creates a new project from the yuf skeleton with DDEV, initializes Git and makes the first commit.
# Usage: bash <(curl -fsSL https://raw.githubusercontent.com/Actra-AG/yuf-skeleton/main/setup.sh) [project-name]
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

name="${1:-}"
if [[ -z "$name" ]]; then
  # Read from the terminal, also when the script itself comes from stdin
  read -r -p "Project name (lowercase letters, digits and hyphens, the site will be <name>.ddev.site): " name < /dev/tty
fi
[[ "$name" =~ ^[a-z0-9]([a-z0-9-]*[a-z0-9])?$ ]] || fail "Invalid project name '$name'."
[[ ! -e "$name" ]] || fail "'$name' already exists in $(pwd)."

mkdir "$name"
cd "$name"
ddev config --project-type=php --php-version=8.5
ddev composer create-project actra/yuf-skeleton
# Apply the DDEV configuration of the skeleton (Apache, document root public/)
ddev restart

git init --quiet --initial-branch=main
git add --all
git commit --quiet --message="chore: create project from yuf skeleton"

ddev launch
echo
echo "Done: $(pwd), https://$name.ddev.site/"
echo "Next steps: see \"After creating the project\" in README.md."
