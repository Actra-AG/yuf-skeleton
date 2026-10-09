# Views and pages

How the example routes in `public/index.php` find their views, and how to add a page. Details of yuf:
[views.md](https://github.com/Actra-AG/yuf/blob/main/docs/views.md),
[templates.md](https://github.com/Actra-AG/yuf/blob/main/docs/templates.md).

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
