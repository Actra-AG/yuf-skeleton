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
// Replace the placeholders by the copyright holder and the license of your project, then run cs:fix
$header = (require __DIR__ . '/vendor/actra/coding-standard/config/php-cs-fixer-header.php')(
    copyright: '[Your company or name]',
    // MIT for public libraries, proprietary for closed projects
    license: '[License of your project]',
    // Folders with code adapted from third-party libraries keep its license (see standards/php.md, section 2), e.g.
    // ['path' => '/app/phone/', 'license' => 'Apache-2.0']
    thirdParty: [],
);

return new Config()
    ->setRiskyAllowed(true)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        ...$rules,
        'header_comment' => $header['rule'],
    ])
    ->setRuleCustomisationPolicy($header['policy'])
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
