<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace tests\Unit\view\frontend;

use actra\yuf\Core;
use actra\yuf\core\BaseView;
use actra\yuf\core\ContentType;
use actra\yuf\core\CoreSettings;
use actra\yuf\core\EnvironmentSettings;
use actra\yuf\core\HttpRequest;
use actra\yuf\core\HttpStatusCodeEnum;
use actra\yuf\core\Route;
use actra\yuf\core\RouteCollection;
use actra\yuf\core\ViewContext;
use actra\yuf\core\ViewMap;
use app\view\frontend\IndexView;
use FilesystemIterator;
use Override;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Renders the page of IndexView through yuf: Core with a request without globals, the real templates of app/view and
 * a temporary directory for the cache and the logs.
 */
final class IndexViewTest extends TestCase
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

    public function testRendersHelloWorld(): void
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
            httpRequest: new HttpRequest(host: 'example.com'),
        );

        $httpResponse = $core->prepareHttpResponse(
            routeCollection: new RouteCollection(
                routes: [
                    new Route(
                        path: '/',
                        viewDirectory: $core->viewDirectory,
                        viewGroup: 'frontend',
                        defaultFileName: 'index.html',
                        defaultContentType: ContentType::createHtml(),
                        viewFactory: new ViewMap()->add(
                            fileTitle: 'index',
                            create: fn(ViewContext $context): BaseView => new IndexView(context: $context),
                        ),
                    ),
                ],
            ),
            individualSessionHandler: false,
        );

        $this->assertSame(HttpStatusCodeEnum::HTTP_OK, $httpResponse->httpStatusCode);
        $content = (string) $httpResponse->getContentString();
        $this->assertStringContainsString('<title>Hello World</title>', $content);
        $this->assertStringContainsString('<h1>Hello World!</h1>', $content);
    }
}
