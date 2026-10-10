<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

use actra\autoloader\Autoloader;
use actra\autoloader\AutoloaderPath;

require __DIR__ . '/../vendor/autoload.php';

// yuf is loaded by Composer; the classes of the application by actra/autoloader, as in production
Autoloader::register()->addPath(
    autoloaderPath: new AutoloaderPath(
        path: __DIR__ . '/../app/',
        prefix: 'app\\',
    ),
);
