# AGENTS.md

Persistent instructions for developers and AI assistants working in this repository.

## Global standard

This project follows the Actra coding standard, installed as development dependency `actra/coding-standard`
(https://github.com/Actra-AG/coding-standard).

- Read [vendor/actra/coding-standard/AGENTS.md](vendor/actra/coding-standard/AGENTS.md) and the standards linked
  there before working on this project. They are binding. If `vendor/` is missing, run `composer install` first.
- The rules below only **add** project-specific rules or state explicit deviations (with reason). They take precedence
  over the global standard where they conflict.

## Project context

- A project based on the [yuf skeleton](https://github.com/Actra-AG/yuf-skeleton) (framework: actra/yuf, PHP 8.5).
- TODO: describe the project, its users, how versions are released and ongoing goals.

## Project-specific rules

- TODO: replace this placeholder with the rules of this project (architecture, directories, allowed tools).
- TODO: how to run and check the application: the URL of the DDEV site (`https://<project>.ddev.site/`) and which
  pages to check (the example app: `/` and `/auto/` give 200, an unknown page gives 404).
- TODO: adapt the copyright and the license in the file header (`copyright:` and `license:` in
  `.php-cs-fixer.dist.php`), then run `composer cs:fix`. Remove this bullet once done.
- TODO: adapt `name`, `description`, `homepage`, `keywords` and `license` in `composer.json` (they still describe the
  skeleton); `license` is the same as in the file header. Remove this bullet once done.
- Views are classes in `app/view/<viewGroup>/`, registered in the `ViewMap` of their route (`public/index.php`), or,
  for routes without `viewFactory`, found by file name in `app/view/<viewGroup>/php/` (see
  [docs/views.md](docs/views.md)).
- Tests (PHPUnit) are in `tests/Unit`; `tests/Unit/view/frontend/IndexViewTest.php` shows how to render a view
  through `Core` without globals.

## Deviations from the global standard

- View classes found by `ClassNameViewFactory` (routes without `viewFactory`, e.g. `app/view/auto/php/welcome.php`)
  have a lowercase class name equal to the file title (`welcome`) in the namespace `app\view\<viewGroup>\php`, not
  PascalCase ([naming.md](vendor/actra/coding-standard/standards/naming.md)). Reason: yuf builds the class name from
  the requested file name. Applies only to these view classes; everything else in them follows the standard.
