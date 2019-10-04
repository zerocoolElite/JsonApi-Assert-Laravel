<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;

/**
 * This trait adds the ability to test included collection.
 */
trait AssertIncluded
{
    /**
     * Asserts that an include object contains an expected collection.
     *
     * @param TestResponse $response
     * @param array        $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseContainsInclude(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertDocumentContainsInclude($expected, $json);
    }
}
