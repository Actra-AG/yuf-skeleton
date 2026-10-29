<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

use actra\yuf\App;
use actra\yuf\core\BaseView;
use actra\yuf\core\ViewContext;
use actra\yuf\core\ViewMap;
use app\view\frontend\IndexView;

require __DIR__ . '/../vendor/autoload.php';

// Reads .env.php and creates missing app/ directories; Composer loads the classes of yuf and of app/
App::fromEnvironment(envFilePath: __DIR__ . '/../.env.php', copyrightYear: 2026)
    ->withLanguage(code: 'en', locale: 'en_US.UTF-8')
    // Explicit ViewMap: maps the file name "index" to app\view\frontend\IndexView (any class name, constructor
    // dependencies possible). "/" shows it; "/index.html" redirects to "/"
    ->withHtmlRoute(
        path: '/',
        viewGroup: 'frontend',
        viewFactory: new ViewMap()->add(
            fileTitle: 'index',
            create: fn(ViewContext $context): BaseView => new IndexView(context: $context),
        ),
    )
    // Automatic detection by file name (no viewFactory, nothing registered): /auto/<name>.html uses the class
    // app\view\auto\php\<name> if it exists, otherwise html/<name>.html is rendered without view
    ->withHtmlRoute(path: '/auto/', viewGroup: 'auto', defaultFileTitle: 'welcome')
    // No PHP session needed for this example
    ->withoutSession()
    ->run();
