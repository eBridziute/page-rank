<?php

declare(strict_types=1);

namespace Tests\Feature\Updaters;

use App\Updaters\PageRankUpdater;
use Database\Seeders\PageRankSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRankUpdaterTest extends TestCase
{
    use RefreshDatabase;

    private readonly PageRankUpdater $pageRankUpdater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pageRankUpdater = new PageRankUpdater();
    }


    public function test_existing_page_ranks_update(): void
    {
        $this->seed(PageRankSeeder::class);

        $this->pageRankUpdater->updateOrCreatePageRanks([
            'test2.com' => null,
            'test.com' => 12
        ]);

        $this->assertDatabaseMissing( 'page_ranks', [
            'domain' => 'test2.com',
            'rank' => 2
        ]);

        $this->assertDatabaseMissing('page_ranks', [
            'domain' => 'test.com',
            'rank' => 1
        ]);

        $this->assertNewRecords();
    }

    public function test_create_page_ranks(): void
    {
        $this->pageRankUpdater->updateOrCreatePageRanks([
            'test2.com' => null,
            'test.com' => 12
        ]);

        $this->assertNewRecords();
    }

    private function assertNewRecords(): void
    {
        $this->assertDatabaseHas('page_ranks', [
            'domain' => 'test2.com',
            'rank' => null
        ]);

        $this->assertDatabaseHas('page_ranks', [
            'domain' => 'test.com',
            'rank' => 12
        ]);
    }
}
