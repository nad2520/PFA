<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FilteringFunctionalTest extends TestCase
{
    public function testSearchEndpointReturnsJsonForAUserSuppliedFilter(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();

        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            '/PFA/api/books/search?q=Alpha',
            $fixture
        );

        $this->assertSame('', $response['stderr']);
        $this->assertSame(200, $response['status']);
        $this->assertTrue($response['json']['success']);
        $this->assertSame(1, $response['json']['count']);
        $this->assertSame('Alpha Fantasy', $response['json']['data'][0]['title']);
    }

    public function testCatalogEndpointReturnsTheTransformedCatalogPayload(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();

        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            '/PFA/api/catalog/books',
            $fixture
        );

        $this->assertSame('', $response['stderr']);
        $this->assertSame(200, $response['status']);
        $this->assertTrue($response['json']['success']);
        $firstBook = $response['json']['data'][0];
        $this->assertSame(['id', 'title', 'author', 'publicationYear', 'genre', 'genres', 'cover', 'trending', 'description', 'audience', 'coinCost', 'xpReward', 'coinReward'], array_keys($firstBook));
        $this->assertSame([1, 2, 3], array_column($response['json']['data'], 'id'));
    }

    public function testEmptySearchQueryReturnsABadRequestResponse(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();

        $response = FilteringTestHelper::runHttpRequest(
            'GET',
            '/PFA/api/books/search?q=',
            $fixture
        );

        $this->assertSame(400, $response['status']);
        $this->assertFalse($response['json']['success']);
        $this->assertSame('Query is required', $response['json']['message']);
    }
}
