# yuf skeleton

A minimal "Hello World" application to start a new project with the [yuf](https://github.com/Actra-AG/yuf) framework.

## Create a project

```bash
composer create-project actra/yuf-skeleton my-project
cd my-project
```

This installs yuf and creates `.env.php` from `.env.example.php`.

## Run it

**With [DDEV](https://ddev.com)** (PHP 8.5, Apache):

1. Set `allowedDomains` in `.env.php` to `my-project.ddev.site` (DDEV uses the directory name).
2. Start DDEV and open the project:
   ```bash
   ddev start
   ddev launch
   ```

**Without DDEV:** use PHP 8.5 with the extensions required by yuf and a web server with `public/` as the document
root. HTTPS is required (yuf redirects HTTP to HTTPS), and all requests for non-existent files must go to
`public/index.php` (see `public/.htaccess` for Apache). Add your host name to `allowedDomains` in `.env.php`.

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
  error_docs/               # error pages (debug, not found, unauthorized, default)
  cache/, logs/             # created at runtime (not committed)
AGENTS.md                   # instructions for developers and AI assistants (refers to the coding standard)
.php-cs-fixer.dist.php      # code style (rules of the coding standard)
phpstan.neon                # static analysis (config of the coding standard)
```

### Git: allowlist

`.gitignore` ignores everything except the files and directories it explicitly lists. This prevents accidental commits
of secrets, database dumps, builds or editor files. When you add a new top-level file or directory (e.g. `src/` or
`tests/`), add it to `.gitignore`, otherwise it is not committed. Check with `git status --ignored` if a file is
missing.

## How a request is handled

For `https://my-project.ddev.site/` (or `/index.html`):

1. `public/index.php` creates `Core` with `Core::fromEnvironment()` (it reads `.env.php`) and registers the routes.
2. The route `/` with the view group `frontend` matches. The file name defaults to `index.html`.
3. The `ViewMap` of the route maps the file name `index` to the view `app\view\frontend\IndexView`; yuf creates it with
   the `ViewContext` of the request. Its `execute()` method sets the values for the page.
4. The template `templates/default.html` is rendered, and `<tst:loadSubTpl tplfile="{this}"/>` includes the content
   file `html/index.html`. `{tst:text value='greeting'}` prints a value set by the view. `addText()` escapes plain
   texts, `addHtml()` outputs trusted HTML as it is.

## Add a page

To add `/about.html`:

1. Create `app/view/frontend/html/about.html` with the content.
2. Create the view class `app/view/frontend/AboutView.php` (a copy of `IndexView`). It must set every value the
   template and content use (e.g. `title`), otherwise rendering fails with a clear error message.
3. Register it in the `ViewMap` of the route in `public/index.php`:
   ```php
   viewFactory: new ViewMap()
       ->add(fileTitle: 'index', create: fn(ViewContext $context): BaseView => new IndexView(context: $context))
       ->add(fileTitle: 'about', create: fn(ViewContext $context): BaseView => new AboutView(context: $context)),
   ```

## Checks

```bash
composer check
```

Runs the code style check (PHP-CS-Fixer), PHPStan (level 10, strict) for `app/` and `public/`, and the tests (none
yet). Fix the code style with `composer cs:fix`. With DDEV: `ddev composer check`.

The project follows the [Actra coding standard](https://github.com/Actra-AG/coding-standard)
(`actra/coding-standard`, a dev dependency). `composer create-project` creates an `AGENTS.md` that refers to it and has
a "Project-specific rules" section for your own rules. Adapt the copyright and license in the file header
(`.php-cs-fixer.dist.php`) and run `composer cs:fix`.

## Production

- Set `'debug' => false`, your real domain(s) in `allowedDomains`, and a valid `logEmailRecipient` in `.env.php`.
- Install without development tools: `composer install --no-dev`.
- Use `opcache.validate_timestamps=0` and reset the opcache on every deployment (see "Production settings" in the
  [yuf README](https://github.com/Actra-AG/yuf#production-settings)).

## License

MIT, see [LICENSE](LICENSE).