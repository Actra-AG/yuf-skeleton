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
  index.php                 # front controller: initializes yuf and defines the routes
  .htaccess                 # sends all requests to index.php
app/
  view/frontend/            # view group "frontend" of the route "/"
    php/index.php           # view class for /index.html
    html/index.html         # content of /index.html
    templates/default.html  # page layout around the content
  error_docs/               # error pages (debug, not found, unauthorized, default)
  cache/, logs/             # created at runtime (not committed)
```

### Git: allowlist

`.gitignore` ignores everything except the files and directories it explicitly lists. This prevents accidental commits
of secrets, database dumps, builds or editor files. When you add a new top-level file or directory (e.g. `src/` or
`tests/`), add it to `.gitignore`, otherwise it is not committed. Check with `git status --ignored` if a file is
missing.

## How a request is handled

For `https://my-project.ddev.site/` (or `/index.html`):

1. `public/index.php` initializes `Core` with `.env.php` and registers the routes.
2. The route `/` with the view group `frontend` matches. The file name defaults to `index.html`.
3. yuf creates the view class `app\view\frontend\php\index`: `app\view\` + view group + `\php\` + file name without
   extension. Its `execute()` method sets the values for the page.
4. The template `templates/default.html` is rendered, and `<tst:loadSubTpl tplfile="{this}"/>` includes the content
   file `html/index.html`. `{tst:text value='greeting'}` prints a value set by the view, HTML-escaped.

## Add a page

To add `/about.html`:

1. Create `app/view/frontend/html/about.html` with the content.
2. Create the view class `app/view/frontend/php/about.php` (class `about`, a copy of `index`). It must set every value
   the template and content use (e.g. `title`), otherwise rendering fails with a clear error message.

## Checks

```bash
composer check
```

Runs PHPStan (level 10) for `app/` and `public/`. With DDEV: `ddev composer check`.

## Production

- Set `'debug' => false`, your real domain(s) in `allowedDomains`, and a valid `logEmailRecipient` in `.env.php`.
- Install without development tools: `composer install --no-dev`.

## License

MIT, see [LICENSE](LICENSE).