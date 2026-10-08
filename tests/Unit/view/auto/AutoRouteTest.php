<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace tests\Unit\view\auto;

use actra\yuf\Core;
use actra\yuf\core\ContentType;
use actra\yuf\core\CoreSettings;
use actra\yuf\core\EnvironmentSettings;
use actra\yuf\core\HttpRequest;
use actra\yuf\core\HttpResponse;
use actra\yuf\core\HttpStatusCodeEnum;
use actra\yuf\core\Route;
use actra\yuf\core\RouteCollection;
use actra\yuf\exception\NotFoundException;
use FilesystemIterator;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Renders the pages of the route "/auto/" (automatic view detection, no viewFactory) through yuf, like IndexViewTest.
 */
final class AutoRouteTest extends TestCase
{
    private string $workDirectory;

    #[Override]
    protected function setUp(): void
    {
        $this->workDirectory = sys_get_temp_dir() . '/yuf-skeleton-test-' . bin2hex(string: random_bytes(length: 8)) . '/';
        mkdir(directory: $this->workDirectory . 'cache', recursive: true);
        mkdir(directory: $this->workDirectory . 'logs');
    }

    #[Override]
    protected function tearDown(): void
    {
        // Core::prepareHttpResponse() registers the exception handler, which must not stay for other tests
        restore_exception_handler();
        $iterator = new RecursiveIteratorIterator(
            iterator: new RecursiveDirectoryIterator(
                directory: $this->workDirectory,
                flags: FilesystemIterator::SKIP_DOTS,
            ),
            mode: RecursiveIteratorIterator::CHILD_FIRST,
        );
        /** @var SplFileInfo $entry */
        foreach ($iterator as $entry) {
            $entry->isDir() ? rmdir(directory: $entry->getPathname()) : unlink(filename: $entry->getPathname());
        }
        rmdir(directory: $this->workDirectory);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function pageProvider(): array
    {
        return [
            'default file, view found by file name' => ['/auto/', '<h1>Welcome!</h1>'],
            'view found by file name' => ['/auto/welcome.html', '<h1>Welcome!</h1>'],
            'HTML only, no view' => ['/auto/about.html', '<h1>About</h1>'],
        ];
    }

    #[DataProvider('pageProvider')]
    public function testRendersPage(string $uri, string $expectedHeading): void
    {
        $httpResponse = $this->handle(uri: $uri);

        $this->assertSame(HttpStatusCodeEnum::HTTP_OK, $httpResponse->httpStatusCode);
        $content = (string) $httpResponse->getContentString();
        $this->assertStringContainsString('<title>Automatic view detection</title>', $content);
        $this->assertStringContainsString($expectedHeading, $content);
    }

    public function testUnknownPageIsNotFound(): void
    {
        // Without class and content file: the exception handler of yuf answers with the 404 error page
        $this->expectException(NotFoundException::class);

        $this->handle(uri: '/auto/unknown.html');
    }

    private function handle(string $uri): HttpResponse
    {
        $appDirectory = dirname(path: __DIR__, levels: 4) . '/app/';
        $core = new Core(
            settings: new CoreSettings(
                environmentSettings: new EnvironmentSettings(
                    errorReporting: E_ALL,
                    timeZone: 'UTC',
                    allowedDomains: ['example.com'],
                    logEmailRecipient: '',
                    debug: false,
                    robots: 'noindex',
                ),
                copyrightYear: 2026,
                documentRoot: $this->workDirectory . 'public/',
                frameworkDirectory: dirname(path: __DIR__, levels: 4) . '/vendor/actra/yuf/src/',
                baseDirectory: $this->workDirectory,
                appDirectory: $appDirectory,
                cacheDirectory: $this->workDirectory . 'cache/',
                errorDocsDirectory: $appDirectory . 'error_docs/',
                logDirectory: $this->workDirectory . 'logs/',
                settingsDirectory: $appDirectory . 'settings/',
                snippetsDirectory: $appDirectory . 'snippets/',
                viewDirectory: $appDirectory . 'view/',
            ),
            httpRequest: new HttpRequest(host: 'example.com', uri: $uri),
        );

        return $core->prepareHttpResponse(
            routeCollection: new RouteCollection(
                routes: [
                    new Route(
                        path: '/auto/',
                        viewDirectory: $core->viewDirectory,
                        viewGroup: 'auto',
                        defaultFileName: 'welcome.html',
                        defaultContentType: ContentType::createHtml(),
                    ),
                ],
            ),
            individualSessionHandler: false,
        );
    }
}
