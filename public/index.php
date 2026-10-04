<?php

declare(strict_types=1);

use actra\yuf\Core;
use actra\yuf\core\ContentType;
use actra\yuf\core\Language;
use actra\yuf\core\Route;
use actra\yuf\core\RouteCollection;

require __DIR__ . '/../vendor/actra/yuf/src/Core.php';

// Registers actra/autoloader (classes with the prefix "app\" are loaded from app/) and creates missing app/ directories
$core = new Core(
    envFilePath: __DIR__ . '/../.env.php',
    copyrightYear: 2026
);
$english = new Language(code: 'en', locale: 'en_US.UTF-8');
$core->availableLanguages->add(language: $english);
$core->prepareHttpResponse(
    routeCollection: new RouteCollection(
        routes: [
            // "/" and "/index.html" are handled by the view class app\view\frontend\php\index
            new Route(
                path: '/',
                viewGroup: 'frontend',
                defaultFileName: 'index.html',
                defaultContentType: ContentType::createHtml(),
                language: $english
            ),
        ]
    ),
    // No PHP session needed for this example
    individualSessionHandler: false
)->sendAndExit();