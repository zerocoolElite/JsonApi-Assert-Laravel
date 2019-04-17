<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;

trait AssertErrorResponse
{
    /**
     * Asserts that an error response (status code 4xx) is valid.
     *
     * @param Illuminate\Foundation\Testing\TestResponse $response
     * @param integer $expectedStatusCode
     * @param array $expectedErrors An array of the expected error objects.
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsErrorResponse(
        TestResponse $response,
        int $expectedStatusCode,
        $expectedErrors,
        bool $strict
    ) {
        $response->assertStatus($expectedStatusCode);

        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        static::assertHasValidStructure($json, $strict);

        // Checks errors member
        static::assertHasErrors($json);
        $errors = $json['errors'];

        static::assertErrorsContains($expectedErrors, $errors, $strict);
    }
}