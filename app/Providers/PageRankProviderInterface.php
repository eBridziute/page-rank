<?php

declare(strict_types=1);

namespace App\Providers;

interface PageRankProviderInterface
{
    /**
     * @param string[] $domains
     *
     * @return array<string,string> key is domain, value - rank
     */
    public function getRanks(array $domains = []): array;
}
