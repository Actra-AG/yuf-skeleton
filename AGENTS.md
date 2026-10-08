# AGENTS.md

Persistent instructions for developers and AI assistants working in this repository (the yuf skeleton itself).

## Global standard

This repository follows the Actra coding standard, installed as development dependency `actra/coding-standard`
(https://github.com/Actra-AG/coding-standard).

- Read [vendor/actra/coding-standard/AGENTS.md](vendor/actra/coding-standard/AGENTS.md) and the standards linked
  there before working on this repository. They are binding. If `vendor/` is missing, run `composer install` first.
- The rules below only **add** project-specific rules or state explicit deviations (with reason).

## Project context

- Starting point of `composer create-project actra/yuf-skeleton`: a minimal "Hello World" on actra/yuf. Every new
  project starts compliant with the standard, so keep `composer check` green and the code the current yuf way.
- Do not add packages (not even dev packages) without asking.
- Releases: Git tags (`vX.Y.Z`), a new yuf requirement or structure change is a new minor or major version.

## Project-specific rules

- `AGENTS.project.md` is the `AGENTS.md` of **new projects**: `composer create-project` copies it to `AGENTS.md`. Keep
  it generic with a "Project-specific rules" placeholder; do not put rules about the skeleton itself there.
- New top-level files or directories go into the `.gitignore` allowlist.
- The file header (`@copyright Actra AG`, `@license MIT`) is the standard header; new projects adapt it in
  `.php-cs-fixer.dist.php`.
- No PHPUnit tests: the skeleton has no logic. The `test` script is a placeholder (see `AGENTS.project.md`).
- Run the application (DDEV): https://yuf-skeleton.ddev.site/ (Hello World 200, unknown page 404).

## Deviations from the global standard

- `composer test` prints a hint instead of running PHPUnit (no tests, PHPUnit is not a dependency of the skeleton).
