<?php

namespace VGirol\JsonApiAssert\Laravel\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\AssertionFailedError;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider;
use VGirol\PhpunitException\SetExceptionsTrait;

abstract class TestCase extends BaseTestCase
{
    use SetExceptionsTrait;

    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            JsonApiAssertServiceProvider::class
        ];
    }

    public function setAssertionFailure(?string $message = null, $code = null): void
    {
        $this->setFailure(AssertionFailedError::class, $message, $code);
    }
}
