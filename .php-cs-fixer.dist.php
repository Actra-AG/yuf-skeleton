<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Config\RuleCustomisationPolicyInterface;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

/** @var array<string, bool|array<string, mixed>> $rules */
$rules = require __DIR__ . '/vendor/actra/coding-standard/config/php-cs-fixer.php';
/**
 * @var Closure(
 *     string $copyright,
 *     string $license,
 *     list<array{path: string, license: string, contains?: string}> $thirdParty
 * ): array{rule: array<string, mixed>, policy: RuleCustomisationPolicyInterface} $headerFactory
 */
$headerFactory = require __DIR__ . '/vendor/actra/coding-standard/config/php-cs-fixer-header.php';
// Replace the placeholders by the copyright holder and the license of your project, then run cs:fix
$header = $headerFactory(
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
