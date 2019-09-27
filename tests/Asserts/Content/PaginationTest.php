<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class PaginationTest extends TestCase
{
    /**
     * @test
     * @dataProvider assertResponseHasNoPaginationLinksProvider
     */
    public function assertResponseHasNoPaginationLinks($content)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseHasNoPaginationLinks($response);
    }

    public function assertResponseHasNoPaginationLinksProvider()
    {
        return [
            'no "links" member' => [
                (new Generator)
                    ->document()
                    ->fakeMeta()
                    ->toArray()
            ],
            'no pagination links' => [
                (new Generator)
                    ->document()
                    ->fakeMeta()
                    ->fakeLinks()
                    ->toArray()
            ]
        ];
    }

    /**
     * @test
     */
    public function assertResponseHasNoPaginationLinksFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = (new Generator)
            ->document()
            ->fakeMeta()
            ->fakeLinks()
            ->addLink(Members::LINK_PAGINATION_LAST, 'url')
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException(sprintf(Messages::NOT_HAS_MEMBER, Members::LINK_PAGINATION_LAST));

        Assert::assertResponseHasNoPaginationLinks($response);
    }

    /**
     * @test
     */
    public function assertResponseHasPaginationLinks()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $links = [
            Members::LINK_PAGINATION_LAST => 'url'
        ];
        $content = (new Generator)
            ->document()
            ->fakeMeta()
            ->fakeLinks()
            ->addLinks($links)
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseHasPaginationLinks($response, $links);
    }

    /**
     * @test
     * @dataProvider assertResponseHasPaginationLinksProvider
     */
    public function assertResponseHasPaginationLinksFailed($content, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = [
            Members::LINK_PAGINATION_LAST => 'url'
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertResponseHasPaginationLinks($response, $expected);
    }

    public function assertResponseHasPaginationLinksProvider()
    {
        return [
            'no "links" member' => [
                (new Generator)
                    ->document()
                    ->fakeMeta()
                    ->toArray(),
                sprintf(Messages::HAS_MEMBER, 'links')
            ],
            'no pagination links' => [
                (new Generator)
                    ->document()
                    ->fakeMeta()
                    ->fakeLinks()
                    ->toArray(),
                null
            ]
        ];
    }

    /**
     * @test
     * @dataProvider assertResponseHasNoPaginationMetaProvider
     */
    public function assertResponseHasNoPaginationMeta($content)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseHasNoPaginationMeta($response);
    }

    public function assertResponseHasNoPaginationMetaProvider()
    {
        return [
            'no "meta" member' => [
                (new Generator)
                    ->document()
                    ->fakeLinks()
                    ->toArray()
            ],
            'no pagination meta' => [
                (new Generator)
                    ->document()
                    ->fakeLinks()
                    ->fakeMeta()
                    ->toArray()
            ]
        ];
    }

    /**
     * @test
     */
    public function assertResponseHasNoPaginationMetaFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = (new Generator)
            ->document()
            ->fakeMeta()
            ->addToMeta(Members::META_PAGINATION, ['error' => 'not allowed'])
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException(sprintf(Messages::NOT_HAS_MEMBER, Members::META_PAGINATION));

        Assert::assertResponseHasNoPaginationMeta($response);
    }

    /**
     * @test
     */
    public function assertResponseHasPaginationMeta()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = [
            'key' => 'value'
        ];

        $content = (new Generator)
            ->document()
            ->fakeMeta()
            ->addToMeta(Members::META_PAGINATION, $expected)
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseHasPaginationMeta($response, $expected);
    }

    /**
     * @test
     * @dataProvider assertResponseHasPaginationMetaFailedProvider
     */
    public function assertResponseHasPaginationMetaFailed($content, $expected, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertResponseHasPaginationMeta($response, $expected);
    }

    public function assertResponseHasPaginationMetaFailedProvider()
    {
        $doc = (new Generator)
            ->document()
            ->fakeLinks();

        $expected = [
            'key' => 'value'
        ];

        return [
            'no "meta" member' => [
                (new Generator)
                    ->document()
                    ->fakeLinks()
                    ->toArray(),
                $expected,
                sprintf(Messages::HAS_MEMBER, 'meta')
            ],
            'no pagination meta' => [
                (new Generator)
                    ->document()
                    ->fakeLinks()
                    ->fakeMeta()
                    ->toArray(),
                $expected,
                sprintf(Messages::HAS_MEMBER, Members::META_PAGINATION)
            ],
            'not equal' => [
                (new Generator)
                    ->document()
                    ->fakeLinks()
                    ->fakeMeta()
                    ->addToMeta(Members::META_PAGINATION, ['error' => 'not equal'])
                    ->toArray(),
                $expected,
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function assertResponseHasPagination()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $links = [
            Members::LINK_PAGINATION_LAST => 'url'
        ];
        $meta = ['key' => 'value'];

        $content = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->addToMeta(Members::META_PAGINATION, $meta)
            ->fakeLinks()
            ->addLinks($links)
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseHasPagination($response, $links, $meta);
    }

    /**
     * @test
     * @dataProvider assertResponseHasPaginationProvider
     */
    public function assertResponseHasPaginationFailed($content, $expectedLinks, $expectedMeta, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertResponseHasPagination($response, $expectedLinks, $expectedMeta);
    }

    public function assertResponseHasPaginationProvider()
    {
        $links = [
            Members::LINK_PAGINATION_LAST => 'url'
        ];
        $meta = [
            'key' => 'value'
        ];

        return [
            'no pagination links' => [
                (new Generator)
                    ->document()
                    ->fakeData()
                    ->fakeMeta()
                    ->fakeLinks()
                    ->addToMeta(Members::META_PAGINATION, $meta)
                    ->toArray(),
                $links,
                $meta,
                null
            ],
            'no pagination meta' => [
                (new Generator)
                    ->document()
                    ->fakeData()
                    ->fakeMeta()
                    ->fakeLinks()
                    ->addLinks($links)
                    ->toArray(),
                $links,
                $meta,
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function assertResponseHasNoPagination()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks()
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseHasNoPagination($response);
    }

    /**
     * @test
     * @dataProvider assertResponseHasNoPaginationProvider
     */
    public function assertResponseHasNoPaginationFailed($content, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertResponseHasNoPagination($response);
    }

    public function assertResponseHasNoPaginationProvider()
    {
        return [
            'with pagination meta' => [
                (new Generator)
                    ->document()
                    ->fakeData()
                    ->fakeMeta()
                    ->fakeLinks()
                    ->addToMeta(Members::META_PAGINATION, ['key' => 'value'])
                    ->toArray(),
                sprintf(Messages::NOT_HAS_MEMBER, Members::META_PAGINATION)
            ],
            'with pagination links' => [
                (new Generator)
                    ->document()
                    ->fakeData()
                    ->fakeMeta()
                    ->fakeLinks()
                    ->addLinks([Members::LINK_PAGINATION_LAST => 'url'])
                    ->toArray(),
                sprintf(Messages::NOT_HAS_MEMBER, Members::LINK_PAGINATION_LAST)
            ]
        ];
    }
}
