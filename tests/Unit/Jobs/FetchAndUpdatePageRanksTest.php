<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\FetchAndUpdatePageRanks;
use App\Providers\PageRankProviderInterface;
use App\Updaters\PageRankUpdater;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FetchAndUpdatePageRanksTest extends TestCase
{
    private readonly FetchAndUpdatePageRanks $fetchAndUpdatePageRanks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fetchAndUpdatePageRanks = new FetchAndUpdatePageRanks();
    }

    public function testHandle(): void
    {
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->expects(self::once())
            ->method('get')
            ->with('top-sites.json')
            ->willReturn('[
                {
                    "rank": 1,
                    "rootDomain": "www.blogger.com",
                    "linkingRootDomains": 100,
                    "domainAuthority": 0
                },
                {
                    "rank": 2,
                    "rootDomain": "www.google.com",
                    "linkingRootDomains": 100,
                    "domainAuthority": 0
                },
                {
                    "rank": 3,
                    "rootDomain": "youtube.com",
                    "linkingRootDomains": 100,
                    "domainAuthority": 0
                }]');

        $pageRanksArray = [
            'www.blogger.com' => null,
            'www.google.com' => 1,
            'youtube.com' => 3
        ];

        $pageRankProvider = $this->createMock(PageRankProviderInterface::class);
        $pageRankProvider->expects(self::once())
            ->method('getRanks')
            ->with(['www.blogger.com', 'www.google.com', 'youtube.com'])
            ->willReturn($pageRanksArray);

        $pageRankUpdater = $this->createMock(PageRankUpdater::class);
        $pageRankUpdater->expects(self::once())
        ->method('updateOrCreatePageRanks')
        ->with($pageRanksArray);

        Storage::shouldReceive('disk')->once()
            ->with('local')
            ->andReturn($fileSystem);

        $this->fetchAndUpdatePageRanks->handle($pageRankProvider, $pageRankUpdater);
    }
}
