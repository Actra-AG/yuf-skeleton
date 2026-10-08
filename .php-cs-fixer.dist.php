<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

/** @var array<string, bool|array<string, mixed>> $rules */
$rules = require __DIR__ . '/vendor/actra/coding-standard/config/php-cs-fixer.php';

return new Config()
    ->setRiskyAllowed(true)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        ...$rules,
        // Replace the placeholders by the copyright holder and the license of your project, then run cs:fix
        'header_comment' => [
            'header' => "@copyright [Your company or name]\n@license   [License of your project]",
            'comment_type' => 'PHPDoc',
            'location' => 'after_open',
            'separate' => 'both',
        ],
    ])
    ->setFinder(
        Finder::create()
            ->in([
                __DIR__ . '/app',
                __DIR__ . '/public',
                __DIR__ . '/tests',
            ])
            // Template cache, generated at runtime
            ->exclude(['cache'])
            ->append([__FILE__, __DIR__ . '/.env.example.php']),
    );
