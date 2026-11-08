# AGENTS.md

Read [vendor/actra/coding-standard/AGENTS.md](vendor/actra/coding-standard/AGENTS.md) and the standards linked there
first; they are binding (missing `vendor/`: run `composer install`).

## Project context

- Based on the [yuf skeleton](https://github.com/Actra-AG/yuf-skeleton).
- TODO: describe the project, its users, how versions are released and ongoing goals.

## Project-specific rules

- TODO: replace this placeholder with the rules of this project (architecture, directories, allowed tools).
- TODO: whether changes of HTML templates and CSS need a frontend review (frontend developer or designer, not
  named).
- TODO: how to run and check the application: the URL of the DDEV site (`https://<project>.ddev.site/`) and which
  pages to check (the example app: `/` and `/auto/` give 200, an unknown page gives 404).
- TODO: adapt the copyright and the license in the file header (`copyright:` and `license:` in
  `.php-cs-fixer.dist.php`), then run `composer cs:fix`. Remove this bullet once done.
- TODO: adapt `name`, `description`, `homepage`, `keywords` and `license` in `composer.json` (they still describe the
  skeleton). Remove this bullet once done.
- Tests: `tests/Double/TestApplication.php` builds the application of `public/index.php` without globals (keep its
  routes in line); `tests/Unit/view/frontend/IndexViewTest.php` shows how to test a page and a redirect. Add new
  directories with PHP code to `DIRECTORIES` in `tests/Unit/StrictTypesTest.php`.

## Deviations from the global standard

- None.
