<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Providers\PageRankProviderInterface;
use App\Updaters\PageRankUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FetchAndUpdatePageRanks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const PAGE_RANKS_FILE_PATH = 'top-sites.json';

    private const BATCH_SIZE = 100;

    public function handle(PageRankProviderInterface $pageRankProvider, PageRankUpdater $pageRankUpdater): void
    {
        $pageRanksFile = Storage::disk('local')->get(self::PAGE_RANKS_FILE_PATH);
        if (null === $pageRanksFile) {
            Log::error('Page ranks file not found at ' . self::PAGE_RANKS_FILE_PATH);

            return;
        }

        $pageRanksFromFile = json_decode($pageRanksFile, true);
        foreach(array_chunk($pageRanksFromFile, self::BATCH_SIZE) as $batch) {
            $domains = [];
            foreach ($batch as $pageRank) {
                if (!isset($pageRank['rootDomain'])) {
                    continue;
                }

                $domains[] = $pageRank['rootDomain'];
            }

            $newRanks = $pageRankProvider->getRanks($domains);
            $pageRankUpdater->updateOrCreatePageRanks($newRanks);
        }
    }
}
