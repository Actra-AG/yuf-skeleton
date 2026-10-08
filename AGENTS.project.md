# AGENTS.md

Persistent instructions for developers and AI assistants working in this repository.

## Global standard

This project follows the Actra coding standard, installed as development dependency `actra/coding-standard`
(https://github.com/Actra-AG/coding-standard).

- Read [vendor/actra/coding-standard/AGENTS.md](vendor/actra/coding-standard/AGENTS.md) and the standards linked
  there before working on this project. They are binding. If `vendor/` is missing, run `composer install` first.
- The rules below only **add** project-specific rules or state explicit deviations (with reason). They take precedence
  over the global standard where they conflict.
- `composer check` (code style, PHPStan, tests) must be green. Fix the code style with `composer cs:fix`.

## Project context

- A project based on the [yuf skeleton](https://github.com/Actra-AG/yuf-skeleton) (framework: actra/yuf).
- TODO: describe the project, its users and how versions are released.

## Project-specific rules

- TODO: replace this placeholder with the rules of this project (architecture, directories, allowed tools).
- Adapt the copyright and the license in the file header (`header_comment` in `.php-cs-fixer.dist.php`), then run
  `composer cs:fix`.
- Views are classes in `app/view/<viewGroup>/`, registered in the `ViewMap` of their route (`public/index.php`).
- The `test` script is a placeholder. Add PHPUnit (`composer require --dev phpunit/phpunit`) and set the script to
  `phpunit` as soon as the project has logic to test.
- Run the application and the checks with DDEV (`ddev composer check`) if the project has a `.ddev/` configuration.

## Deviations from the global standard

- None.
