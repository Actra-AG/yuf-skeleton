<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace tests\Unit\view\frontend;

use actra\yuf\core\HttpStatusCodeEnum;
use actra\yuf\core\RecordingResponseSender;
use actra\yuf\core\ResponseSender;
use Override;
use PHPUnit\Framework\TestCase;
use tests\Double\TestApplication;

/**
 * Renders pages of the application through `App` without globals (`TestApplication`).
 */
final class IndexViewTest extends TestCase
{
    private TestApplication $testApplication;

    #[Override]
    protected function setUp(): void
    {
        $this->testApplication = new TestApplication();
    }

    #[Override]
    protected function tearDown(): void
    {
        $this->testApplication->cleanUp();
    }

    public function testRendersHelloWorld(): void
    {
        $httpResponse = $this->testApplication
            ->createApp(uri: '/', responseSender: new RecordingResponseSender())
            ->prepare();

        $this->assertSame(HttpStatusCodeEnum::HTTP_OK, $httpResponse->httpStatusCode);
        $content = (string) $httpResponse->getContentString();
        $this->assertStringContainsString('<title>Hello World</title>', $content);
        $this->assertStringContainsString('<h1>Hello World!</h1>', $content);
    }

    public function testDefaultFileNameIsRedirectedToTheRoot(): void
    {
        // The redirect ends the script: RecordingResponseSender records it instead
        $httpResponse = RecordingResponseSender::capture(
            action: function (ResponseSender $responseSender): void {
                $this->testApplication->createApp(uri: '/index.html', responseSender: $responseSender)->prepare();
            },
        );

        $this->assertSame(HttpStatusCodeEnum::HTTP_MOVED_PERMANENTLY, $httpResponse->httpStatusCode);
        $this->assertSame('https://example.com/', $httpResponse->getHeader(key: 'Location'));
    }
}
