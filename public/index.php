<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

use actra\yuf\Core;
use actra\yuf\core\BaseView;
use actra\yuf\core\ContentType;
use actra\yuf\core\Language;
use actra\yuf\core\Route;
use actra\yuf\core\RouteCollection;
use actra\yuf\core\ViewContext;
use actra\yuf\core\ViewMap;
use app\view\frontend\IndexView;

require __DIR__ . '/../vendor/autoload.php';

// Reads .env.php and creates missing app/ directories; Composer loads the classes of yuf and of app/
$core = Core::fromEnvironment(
    envFilePath: __DIR__ . '/../.env.php',
    copyrightYear: 2026,
);
$english = new Language(code: 'en', locale: 'en_US.UTF-8');
$core->availableLanguages->add(language: $english);
$core->prepareHttpResponse(
    routeCollection: new RouteCollection(
        routes: [
            // Explicit ViewMap: maps the file name "index" to app\view\frontend\IndexView (any class name, constructor
            // dependencies possible)
            new Route(
                path: '/',
                viewDirectory: $core->viewDirectory,
                viewGroup: 'frontend',
                defaultFileName: 'index.html',
                defaultContentType: ContentType::createHtml(),
                language: $english,
                viewFactory: new ViewMap()->add(
                    fileTitle: 'index',
                    create: fn(ViewContext $context): BaseView => new IndexView(context: $context),
                ),
            ),
            // Automatic detection by file name (no viewFactory, nothing registered): /auto/<name>.html uses the class
            // app\view\auto\php\<name> if it exists, otherwise html/<name>.html is rendered without view
            new Route(
                path: '/auto/',
                viewDirectory: $core->viewDirectory,
                viewGroup: 'auto',
                defaultFileName: 'welcome.html',
                defaultContentType: ContentType::createHtml(),
                language: $english,
            ),
        ],
    ),
    // No PHP session needed for this example
    individualSessionHandler: false,
)->sendAndExit();
