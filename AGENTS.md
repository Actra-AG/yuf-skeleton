# AGENTS.md

Instructions for working on the yuf skeleton itself. Read
[vendor/actra/coding-standard/AGENTS.md](vendor/actra/coding-standard/AGENTS.md) and the standards linked there first;
they are binding (missing `vendor/`: run `composer install`).

## Project context

- Starting point of `composer create-project actra/yuf-skeleton`: a minimal "Hello World" on actra/yuf. Every new
  project starts compliant with the standard and on the current yuf way.

## Project-specific rules

- `AGENTS.project.md` becomes the `AGENTS.md` of new projects. Keep it generic, with `TODO:` placeholders; no rules
  about the skeleton itself.
- The file header keeps its neutral placeholders (`[Your company or name]`, `[License of your project]` in
  `.php-cs-fixer.dist.php`); new projects fill them in.
- Develop from a Git clone: `ddev start`, `ddev composer install`, copy `.env.example.php` to `.env.php` and set
  `allowedDomains` to `<directory>.ddev.site`. Check https://yuf-skeleton.ddev.site/: `/` and `/auto/` give 200, an
  unknown page gives 404.
- Test changes of `setup.sh` with a full run in an empty directory outside the repository. It installs the latest
  tag: before tagging, test a copy that installs `actra/yuf-skeleton:dev-main`.
- Releases: the commit adds its section to `CHANGELOG.md` and is tagged; pushing the tag publishes the GitHub release.

## Deviations from the global standard

- `CHANGELOG.md` instead of `UPGRADE.md` (`standards/versioning.md`, section 1). Reason: projects copy the skeleton
  once and are never upgraded from it; they follow yuf's `UPGRADE.md`.
