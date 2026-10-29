<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace tests\Double;

use actra\yuf\App;
use actra\yuf\Core;
use actra\yuf\core\BaseView;
use actra\yuf\core\CoreSettings;
use actra\yuf\core\EnvironmentSettings;
use actra\yuf\core\HttpRequest;
use actra\yuf\core\ResponseSender;
use actra\yuf\core\ViewContext;
use actra\yuf\core\ViewMap;
use app\view\frontend\IndexView;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * The application of `public/index.php` without globals: the same languages and routes, the views and templates of
 * `app/`, cache and logs in a temporary directory. Keep it in line with `public/index.php`.
 */
final readonly class TestApplication
{
    private string $workDirectory;

    public function __construct()
    {
        $this->workDirectory = sys_get_temp_dir() . '/yuf-skeleton-test-' . bin2hex(string: random_bytes(length: 8))
            . '/';
        mkdir(directory: $this->workDirectory . 'cache', recursive: true);
        mkdir(directory: $this->workDirectory . 'logs');
    }

    /**
     * @param ResponseSender $responseSender Receives redirects and other responses that end the script; tests pass a
     *     `RecordingResponseSender`
     */
    public function createApp(string $uri, ResponseSender $responseSender): App
    {
        $projectDirectory = dirname(path: __DIR__, levels: 2) . '/';
        $appDirectory = $projectDirectory . 'app/';
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
                frameworkDirectory: $projectDirectory . 'vendor/actra/yuf/src/',
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
            responseSender: $responseSender,
        );

        // The same as public/index.php
        return App::fromCore(core: $core)
            ->withLanguage(code: 'en', locale: 'en_US.UTF-8')
            ->withHtmlRoute(
                path: '/',
                viewGroup: 'frontend',
                viewFactory: new ViewMap()->add(
                    fileTitle: 'index',
                    create: fn(ViewContext $context): BaseView => new IndexView(context: $context),
                ),
            )
            ->withHtmlRoute(path: '/auto/', viewGroup: 'auto', defaultFileTitle: 'welcome')
            ->withoutSession();
    }

    /**
     * Removes the temporary directory and the exception handler that `App::prepare()` registers.
     */
    public function cleanUp(): void
    {
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
}
