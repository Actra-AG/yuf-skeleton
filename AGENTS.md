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
  project starts compliant with the standard and on the current yuf way.

## Project-specific rules

- `AGENTS.project.md` is the `AGENTS.md` of **new projects**: `composer create-project` copies it to `AGENTS.md`. Keep
  it generic with a "Project-specific rules" placeholder; do not put rules about the skeleton itself there.
- The file header is a neutral placeholder (`[Your company or name]`, `[License of your project]` in
  `.php-cs-fixer.dist.php`), because new projects adapt it. Do not replace it by a real copyright holder.
- Run the application (DDEV): https://yuf-skeleton.ddev.site/ (Hello World 200, unknown page 404).

## Deviations from the global standard

- None.
