<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use App\Providers\OpenPageRankProvider;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class OpenPageRankProviderTest extends TestCase
{
    private readonly OpenPageRankProvider $openPageRankProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->openPageRankProvider = new OpenPageRankProvider();
    }

    public function testGetRanks(): void
    {
        $domains = [
            'test.com',
            'test2.com',
            'test3.com',
        ];

        $response = $this->createMock(Response::class);
        $response->expects(self::once())
            ->method('failed')
            ->with()
            ->willReturn(false);

        $response->expects(self::once())
            ->method('body')
            ->with()
            ->willReturn(
                '{
                    "status_code": 200,
                    "response": [
                        {
                            "status_code": 200,
                            "error": "",
                            "page_rank_integer": 10,
                            "page_rank_decimal": 10,
                            "rank": "6",
                            "domain": "test2.com"
                        },
                        {
                            "status_code": 200,
                            "error": "",
                            "page_rank_integer": 8,
                            "page_rank_decimal": 7.63,
                            "rank": "40",
                            "domain": "test.com"
                        },
                        {
                            "status_code": 404,
                            "error": "Domain not found",
                            "page_rank_integer": 0,
                            "page_rank_decimal": 0,
                            "rank": null,
                            "domain": "test3.com"
                        }
                    ]
                }'
            );

        $pendingRequest = $this->createMock(PendingRequest::class);
        $pendingRequest->expects(self::once())
            ->method('get')
            ->with('https://openpagerank.com/api/v1.0/getPageRank', ['domains' => $domains])
            ->willReturn($response);

        Http::shouldReceive('withHeaders')->once()->with([
            'API-OPR' => env('OPEN_PAGE_RANK_API_KEY')
        ])->andReturn($pendingRequest);

        $this->assertEquals(
            [
                'test2.com' => 6,
                'test.com' => 40,
                'test3.com' => null,
            ],
            $this->openPageRankProvider->getRanks($domains)
        );
    }

    public function testGetRanksWithFailedRequest(): void
    {
        $domains = ['test.com'];

        $response = $this->createMock(Response::class);
        $response->expects(self::once())
            ->method('failed')
            ->with()
            ->willReturn(true);

        $response->expects(self::never())
            ->method('body');

        $pendingRequest = $this->createMock(PendingRequest::class);
        $pendingRequest->expects(self::once())
            ->method('get')
            ->with('https://openpagerank.com/api/v1.0/getPageRank', ['domains' => $domains])
            ->willReturn($response);

        Http::shouldReceive('withHeaders')->once()->with([
            'API-OPR' => env('OPEN_PAGE_RANK_API_KEY')
        ])->andReturn($pendingRequest);

        Log::shouldReceive('error')->once()
            ->with('Failed to get data from Open page rank. Payload: ' . json_encode($domains));

        $this->assertEquals(
            [],
            $this->openPageRankProvider->getRanks($domains)
        );
    }
}
