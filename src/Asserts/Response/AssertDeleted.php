<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertDeleted
{
    /**
     * Asserts that a response object is a valid '200 OK' response following a deletion request.
     *
     * @param Illuminate\Foundation\Testing\TestResponse $response
     * @param array|null $expectedMeta If not null, it is the expected "meta" object.
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsDeletedResponse(
        TestResponse $response,
        $expectedMeta,
        bool $strict
    ) {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        static::assertHasValidStructure($json, $strict);

        static::assertContainsOnlyAllowedMembers(['meta', 'jsonapi'], $json);

        // Checks meta object
        $meta = $json['meta'];
        if (!is_null($expectedMeta)) {
            PHPUnit::assertEquals($expectedMeta, $meta);
        }
    }
}