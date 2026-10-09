# yuf skeleton

A minimal "Hello World" application to start a new project with the [yuf](https://github.com/Actra-AG/yuf) framework.

## Create a project

**With [DDEV](https://ddev.com)** (needs only DDEV and Git, no local PHP or Composer):

```bash
bash <(curl -fsSL https://raw.githubusercontent.com/Actra-AG/yuf-skeleton/main/setup.sh)
```

It asks for the project name, the copyright holder and the license (default: MIT), creates the project in a new
directory, fills in the file header and `allowedDomains` (`<name>.ddev.site`), makes the first Git commit and opens the
Hello World page. Run all further `composer` commands as
`ddev composer`.

**Without DDEV:** run `composer create-project actra/yuf-skeleton my-project` with PHP 8.5 and the extensions required
by yuf. Use a web server with `public/` as the document root, HTTPS (yuf redirects HTTP to HTTPS), and all requests for
non-existent files sent to `public/index.php` (see `public/.htaccess` for Apache). Add your host name to
`allowedDomains` in `.env.php`.

**After creating the project:**

1. Without `setup.sh`: replace the placeholders `[Your company or name]` and `[License of your project]` in the file
   header (`copyright:` and `license:` in `.php-cs-fixer.dist.php`) and run `composer cs:fix`.
2. Adapt `name`, `description`, `homepage`, `keywords` and `license` in `composer.json`: they still describe the
   skeleton.
3. Fill in the `TODO:` items in `AGENTS.md`. It refers to the
   [Actra coding standard](https://github.com/Actra-AG/coding-standard) (`actra/coding-standard`, a dev dependency) and
   has a "Project-specific rules" section for your own rules.

## Structure

```
.env.php                    # environment settings (not committed)
public/
  index.php                 # front controller: initializes yuf and defines the routes and the views
  .htaccess                 # sends all requests to index.php
app/
  view/frontend/            # view group "frontend" of the route "/"
    IndexView.php           # view class for /index.html (registered in public/index.php)
    html/index.html         # content of /index.html
    templates/default.html  # page layout around the content
  view/auto/                # view group "auto" of the route "/auto/" (automatic view detection)
    php/welcome.php         # view class for /auto/welcome.html (found by file name, not registered)
    html/welcome.html       # content of /auto/welcome.html (default file of /auto/)
    html/about.html         # content of /auto/about.html (no view)
    templates/default.html  # page layout of this view group
  error_docs/               # error pages (debug, not found, unauthorized, default)
  cache/, logs/             # created at runtime (not committed)
AGENTS.md                   # instructions for developers and AI assistants (refers to the coding standard)
CLAUDE.md                   # makes Claude Code read AGENTS.md
.php-cs-fixer.dist.php      # code style (rules of the coding standard)
phpstan.neon                # static analysis (config of the coding standard)
tests/                      # PHPUnit tests (phpunit.xml)
docs/                       # documentation of the project (details linked from README.md)
```

`.gitignore` is an allowlist: add every new top-level file or directory (e.g. `src/`) there, otherwise it is not
committed; check with `git status --ignored`.

## Pages and views

`public/index.php` has two routes: `/` registers each view in a `ViewMap`, `/auto/` finds the view by file name. How a
request is handled, how to add a page and when to use which route: [docs/views.md](docs/views.md).

## Checks

```bash
composer check
```

Runs the code style check, PHPStan and the PHPUnit tests. With DDEV: `ddev composer check`.

## Production

- Set `'debug' => false`, your real domain(s) in `allowedDomains`, and a valid `logEmailRecipient` in `.env.php`.
- Install without development tools: `composer install --no-dev`.
- On every deployment, reset the opcache (`opcache.validate_timestamps=0`) and delete `app/cache/v*/`: without
  `debug`, changed templates are not compiled again (see [yuf: Production](vendor/actra/yuf/docs/setup.md#production)).

## License

MIT, see [LICENSE](LICENSE). `composer create-project` removes the `LICENSE` file from new projects: choose your own
license, add a `LICENSE` file if needed, and set it as `license` in `composer.json` and in the file header. Projects
under any license, including proprietary ones, may use yuf (`MIT AND LGPL-2.1-only AND Apache-2.0`, see
[yuf: License](vendor/actra/yuf/README.md#license)).
