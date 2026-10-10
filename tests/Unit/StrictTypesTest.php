<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace tests\Unit;

use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * PHP-CS-Fixer only checks its configured paths: this test finds every PHP file without declare(strict_types=1);
 */
final class StrictTypesTest extends TestCase
{
    // .gitignore is an allowlist: the tracked directories with PHP code (add new ones here as well)
    private const array DIRECTORIES = ['app', 'docs', 'public', 'tests'];
    // Paths relative to the project root with generated code
    private const array EXCLUDED = ['app/cache'];

    public function testEveryPhpFileDeclaresStrictTypes(): void
    {
        $root = dirname(path: __DIR__, levels: 2);
        // Root files (.env.php, .php-cs-fixer.dist.php, …), without the untracked directories of the root
        $files = [];
        foreach (['/*.php', '/.*.php'] as $pattern) {
            $rootFiles = glob(pattern: $root . $pattern);
            if ($rootFiles !== false) {
                array_push($files, ...$rootFiles);
            }
        }
        foreach (StrictTypesTest::DIRECTORIES as $directory) {
            $iterator = new RecursiveIteratorIterator(
                iterator: new RecursiveCallbackFilterIterator(
                    iterator: new RecursiveDirectoryIterator(
                        directory: $root . '/' . $directory,
                        flags: FilesystemIterator::SKIP_DOTS,
                    ),
                    callback: static fn(SplFileInfo $file): bool => !in_array(
                        needle: substr(string: $file->getPathname(), offset: strlen(string: $root) + 1),
                        haystack: StrictTypesTest::EXCLUDED,
                        strict: true,
                    ),
                ),
            );
            foreach ($iterator as $file) {
                if ($file instanceof SplFileInfo && $file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
        }
        $missing = [];
        foreach ($files as $file) {
            $code = file_get_contents(filename: $file);
            if ($code === false || preg_match(pattern: '/^declare\(strict_types=1\);$/m', subject: $code) !== 1) {
                $missing[] = $file;
            }
        }

        $this->assertSame([], $missing);
    }
}
