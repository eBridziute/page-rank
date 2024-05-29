<?php

declare(strict_types=1);

namespace App\Updaters;

use App\Models\PageRank;

class PageRankUpdater
{
    /**
     * @param array<string,string> $pageRanks
     */
    public function updateOrCreatePageRanks(array $pageRanks): void
    {
        foreach ($pageRanks as $domain => $rank) {
            $pageRank = PageRank::updateOrCreate(['domain' => $domain], ['rank' => $rank]);
            $pageRank->save();
        }
    }
}
