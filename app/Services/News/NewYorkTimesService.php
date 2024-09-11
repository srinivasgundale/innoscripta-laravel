<?php

namespace App\Services\News;

use GuzzleHttp\Client;

class NewYorkTimesService implements NewsServiceInterface
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.nytimes.api_key');
        $this->baseUrl = config('services.nytimes.base_url');
    }

    public function searchArticles(array $filters): array
    {
        $response = $this->client->get($this->baseUrl . 'search/v2/articlesearch.json', [
            'query' => array_merge($filters, [
                'api-key' => $this->apiKey,
            ])
        ]);
        //dd($response->getBody()->getContents());
        return json_decode($response->getBody()->getContents(), true)['response']['docs'] ?? [];
    }
}
