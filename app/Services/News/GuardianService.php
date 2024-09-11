<?php

namespace App\Services\News;

use GuzzleHttp\Client;

class GuardianService implements NewsServiceInterface
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.guardian.api_key');
        $this->baseUrl = config('services.guardian.base_url');
    }

    public function searchArticles(array $filters): array
    {
        $response = $this->client->get($this->baseUrl . 'search', [
            'query' => array_merge($filters, [
                'api-key' => $this->apiKey,
                //'q' => $keyword,
            ])
        ]);

        return json_decode($response->getBody()->getContents(), true)['response']['results'] ?? [];
    }
}
