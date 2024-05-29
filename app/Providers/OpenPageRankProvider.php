<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class OpenPageRankProvider implements PageRankProviderInterface
{
    private const URL = 'https://openpagerank.com/api/v1.0/getPageRank';

    /**
     * {@inheritdoc }
     */
    public function getRanks(array $domains = []): array
    {
        try {
            $response = Http::withHeaders([
                'API-OPR' => env('OPEN_PAGE_RANK_API_KEY')
            ])->get(self::URL, [
                'domains' => $domains
            ]);
        } catch (Throwable $e) {
            Log::error($e);

            return [];
        }

        if ($response->failed()) {
            Log::error('Failed to get data from Open page rank. Payload: ' . json_encode($domains));

            return [];
        }

        return $this->getPageRanksFromResponse($response->body());
    }

    /**
     * @return array<string,string>
     */
    private function getPageRanksFromResponse(string $responseBody): array
    {
        $decodedResponseBody = json_decode($responseBody);
        $response = $decodedResponseBody->response;
        $pageRanks = [];
        foreach ($response as $pageRank) {
            $rank = null;
            if (200 === $pageRank->status_code) {
                $rank = $pageRank->rank ?? null;
            }

            if (!isset($pageRank->domain)) {
                continue;
            }

            $pageRanks[$pageRank->domain] = $rank;
        }

        return $pageRanks;
    }
}
