<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FilteringSecurityTest extends TestCase
{
    public function testSearchEndpointDoesNotBroadenResultsForSqlInjectionPayloads(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();

        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            "/PFA/api/books/search?q=" . rawurlencode("%' OR 1=1 --"),
            $fixture
        );

        $this->assertSame(200, $response['status']);
        $this->assertTrue($response['json']['success']);
        $this->assertSame(0, $response['json']['count']);
        $this->assertSame([], $response['json']['data']);
    }

    public function testSearchEndpointRejectsMalformedArrayQueryParameters(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();

        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            '/PFA/api/books/search?q[]=malicious',
            $fixture
        );

        $this->assertSame(400, $response['status']);
        $this->assertFalse($response['json']['success']);
        $this->assertSame('Query is required', $response['json']['message']);
    }

    public function testCatalogEndpointKeepsMaliciousTitlesAsSafeJsonStrings(): void
    {
        $fixture = [
            'books' => [[
                'id' => 7,
                'title' => '<script>alert(1)</script>',
                'author' => 'Payload Writer',
                'publication_year' => 2024,
                'genre' => 'Mystery',
                'cover' => '🧪',
                'trending' => 1,
                'description' => 'payload',
                'audience' => 'All',
                'coinCost' => 1,
                'xpReward' => 1,
                'coinReward' => 1,
            ]],
            'book_genres' => [],
        ];

        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            '/PFA/api/catalog/books',
            $fixture
        );

        $this->assertSame(200, $response['status']);
        $this->assertSame('<script>alert(1)</script>', $response['json']['data'][0]['title']);
    }

    public function testUnauthorizedUsersCannotReachTheFilteringUiPage(): void
    {
        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            '/PFA/user'
        );

        $this->assertContains('Location: /PFA/', $response['headers']);
    }
}
