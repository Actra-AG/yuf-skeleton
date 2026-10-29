<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace tests\Unit\view\auto;

use actra\yuf\core\HttpResponse;
use actra\yuf\core\HttpStatusCodeEnum;
use actra\yuf\core\RecordingResponseSender;
use actra\yuf\core\ResponseSender;
use actra\yuf\exception\NotFoundException;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use tests\Double\TestApplication;

/**
 * The route `/auto/` finds its views by the file name (`ClassNameViewFactory`), without registration.
 */
final class AutoRouteTest extends TestCase
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

    /**
     * @return array<string, array{string, string}>
     */
    public static function pageProvider(): array
    {
        return [
            'default file, view found by file name' => ['/auto/', '<h1>Welcome!</h1>'],
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

    public function testDefaultFileNameIsRedirectedToThePathOfTheRoute(): void
    {
        $httpResponse = RecordingResponseSender::capture(
            action: function (ResponseSender $responseSender): void {
                $this->testApplication->createApp(uri: '/auto/welcome.html', responseSender: $responseSender)
                    ->prepare();
            },
        );

        $this->assertSame(HttpStatusCodeEnum::HTTP_MOVED_PERMANENTLY, $httpResponse->httpStatusCode);
        $this->assertSame('https://example.com/auto/', $httpResponse->getHeader(key: 'Location'));
    }

    public function testUnknownPageIsNotFound(): void
    {
        // Without class and content file: the exception handler of yuf answers with the 404 error page
        $this->expectException(NotFoundException::class);

        $this->handle(uri: '/auto/unknown.html');
    }

    private function handle(string $uri): HttpResponse
    {
        return $this->testApplication->createApp(uri: $uri, responseSender: new RecordingResponseSender())->prepare();
    }
}
