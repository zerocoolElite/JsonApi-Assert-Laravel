<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\HttpHeader;

/**
 * No content response
 */
trait AssertNoContent
{
    /**
     * Asserts that a response is a valid '204 No Content' response.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsNoContentResponse(TestResponse $response)
    {
        $response->assertStatus(204);
        $response->assertHeaderMissing(HttpHeader::HEADER_NAME);

        // Decode JSON response
        $content = $response->getContent();

        PHPUnit::assertEmpty($content);
    }
}
