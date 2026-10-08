<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

use actra\autoloader\Autoloader;
use actra\autoloader\AutoloaderPath;

require __DIR__ . '/../vendor/autoload.php';

// The classes of yuf and of the application are loaded by actra/autoloader, as in production (not by Composer)
$autoloader = Autoloader::register();
$autoloader->addPath(
    autoloaderPath: new AutoloaderPath(
        path: __DIR__ . '/../vendor/actra/yuf/src/',
        prefix: 'actra\\yuf\\',
    ),
);
$autoloader->addPath(
    autoloaderPath: new AutoloaderPath(
        path: __DIR__ . '/../app/',
        prefix: 'app\\',
    ),
);
