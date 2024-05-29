<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PageRankController;
use Database\Seeders\PageRankSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRankControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_rank_list(): void
    {
        $this->seed(PageRankSeeder::class);
        $this->get(action([PageRankController::class, 'index']))
            ->assertStatus(200)
            ->assertSeeInOrder(['<td>1</td><td>test.com</td>', '<td>2</td><td>test2.com</td>'], false);
    }
}
