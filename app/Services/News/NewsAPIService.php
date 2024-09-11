<?php

namespace App\Services\News;

use GuzzleHttp\Client;

class NewsAPIService implements NewsServiceInterface
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.newsapi.api_key');
        $this->baseUrl = config('services.newsapi.base_url');
    }

    public function searchArticles(array $filters): array
    {
        $response = $this->client->get($this->baseUrl . 'everything', [
            'query' => array_merge($filters, [
                'apiKey' => $this->apiKey,
            ])
        ]);

        return json_decode($response->getBody()->getContents(), true)['articles'] ?? [];
    }
}
