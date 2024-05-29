<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageRankSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'domain' => 'test2.com',
                'rank' => 2
            ],
            [
                'domain' => 'test.com',
                'rank' => 1
            ]
        ];

        DB::table('page_ranks')->insert($data);
    }
}
