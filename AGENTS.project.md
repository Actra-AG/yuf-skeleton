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

- A project based on the [yuf skeleton](https://github.com/Actra-AG/yuf-skeleton) (framework: actra/yuf).
- TODO: describe the project, its users and how versions are released.

## Project-specific rules

- TODO: replace this placeholder with the rules of this project (architecture, directories, allowed tools).
- Adapt the copyright and the license in the file header (`header_comment` in `.php-cs-fixer.dist.php`), then run
  `composer cs:fix`.
- Views are classes in `app/view/<viewGroup>/`, registered in the `ViewMap` of their route (`public/index.php`), or,
  for routes without `viewFactory`, found by file name in `app/view/<viewGroup>/php/` (see README.md, "Add a page").
- Tests (PHPUnit) are in `tests/Unit`; `tests/Unit/view/frontend/IndexViewTest.php` shows how to render a view
  through `Core` without globals.

## Deviations from the global standard

- View classes found by `ClassNameViewFactory` (routes without `viewFactory`, e.g. `app/view/auto/php/welcome.php`)
  have a lowercase class name equal to the file title (`welcome`) in the namespace `app\view\<viewGroup>\php`, not
  PascalCase ([naming.md](vendor/actra/coding-standard/standards/naming.md)). Reason: yuf builds the class name from
  the requested file name. Applies only to these view classes; everything else in them follows the standard.
