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
```

### Git: allowlist

`.gitignore` ignores everything except the files and directories it explicitly lists. This prevents accidental commits
of secrets, database dumps, builds or editor files. When you add a new top-level file or directory (e.g. `src/` or
`tests/`), add it to `.gitignore`, otherwise it is not committed. Check with `git status --ignored` if a file is
missing.

## How a request is handled

`public/index.php` has two routes that show both ways to find the view of a page:

- `/` with a `ViewMap`: each view is registered explicitly. Views can have any class name and constructor dependencies.
- `/auto/` without `viewFactory`: yuf finds the view by the file name (`ClassNameViewFactory`). Nothing is registered.

### With ViewMap

For `https://my-project.ddev.site/` (or `/index.html`):

1. `public/index.php` creates `Core` with `Core::fromEnvironment()` (it reads `.env.php`) and registers the routes.
2. The route `/` with the view group `frontend` matches. The file name defaults to `index.html`.
3. The `ViewMap` of the route maps the file name `index` to the view `app\view\frontend\IndexView`; yuf creates it with
   the `ViewContext` of the request. Its `execute()` method sets the values for the page.
4. The template `templates/default.html` is rendered, and `<tst:loadSubTpl tplfile="{this}"/>` includes the content
   file `html/index.html`. `{tst:text value='greeting'}` prints a value set by the view. `addText()` escapes plain
   texts, `addHtml()` outputs trusted HTML as it is.

### Automatic view detection

For `https://my-project.ddev.site/auto/welcome.html` (or `/auto/`, the file name defaults to `welcome.html`):

1. The route `/auto/` with the view group `auto` matches. It has no `viewFactory`.
2. yuf builds the class name from the view group and the file title: `app\view\auto\php\welcome`, loaded from
   `app/view/auto/php/welcome.php`, and creates it with `new $className(context: $context)`.
3. If there is no such class (e.g. `/auto/about.html`), the page is rendered from `html/about.html` without view. If
   there is no content file either, the response is 404.

The class name equals the file title, so these view classes are lowercase (`welcome`). This is the only allowed
deviation from the naming rules of the coding standard (see `AGENTS.md`). A page without view cannot set values, so
the template of `auto` has a fixed title.

## Add a page

**With ViewMap** (route `/`), to add `/about.html`:

1. Create `app/view/frontend/html/about.html` with the content.
2. Create the view class `app/view/frontend/AboutView.php` (a copy of `IndexView`). It must set every value the
   template and content use (e.g. `title`), otherwise rendering fails with a clear error message.
3. Register it in the `ViewMap` of the route in `public/index.php`:
   ```php
   viewFactory: new ViewMap()
       ->add(fileTitle: 'index', create: fn(ViewContext $context): BaseView => new IndexView(context: $context))
       ->add(fileTitle: 'about', create: fn(ViewContext $context): BaseView => new AboutView(context: $context)),
   ```

**Automatic** (route `/auto/`), to add `/auto/contact.html`:

1. Create `app/view/auto/html/contact.html` with the content. This is enough for a static page.
2. If the page needs a view, create `app/view/auto/php/contact.php` with the class `app\view\auto\php\contact` (a copy
   of `welcome`). Nothing has to be registered.

**When to use which:** use the `ViewMap` when views need dependencies (repositories, services) or meaningful class
names, which is the case for most pages of an application. Use the automatic detection for many simple or static pages
that need no dependencies.

## Checks

```bash
composer check
```

Runs the code style check (PHP-CS-Fixer), PHPStan (level 10, strict) for `app/`, `public/` and `tests/`, and the
PHPUnit tests. Fix the code style with `composer cs:fix`. With DDEV: `ddev composer check`.

The project follows the [Actra coding standard](https://github.com/Actra-AG/coding-standard)
(`actra/coding-standard`, a dev dependency). `composer create-project` creates an `AGENTS.md` that refers to it and has
a "Project-specific rules" section for your own rules. Replace the placeholders `[Your company or name]` and
`[License of your project]` in the file header (`.php-cs-fixer.dist.php`) and run `composer cs:fix`. Adapt `name`,
`description`, `homepage`, `keywords` and `license` in `composer.json`: they still describe the skeleton.

## Production

- Set `'debug' => false`, your real domain(s) in `allowedDomains`, and a valid `logEmailRecipient` in `.env.php`.
- Install without development tools: `composer install --no-dev`.
- Use `opcache.validate_timestamps=0` and reset the opcache on every deployment (see "Production settings" in the
  [yuf README](https://github.com/Actra-AG/yuf#production-settings)).

## License

MIT, see [LICENSE](LICENSE). `composer create-project` removes the `LICENSE` file from new projects: choose your own
license, add a `LICENSE` file if needed, and set it as `license` in `composer.json` and in the file header.
