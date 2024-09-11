<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NewsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchFromNewsAPI($query, $category = null, $date = null)
    {
        $key = '3200c1f1b5af4d92ab4c5815798e33f3'; //env('NEWS_API_ORG_KEY');
        //dd($key);
        $url = 'https://newsapi.org/v2/everything';
        $params = [
            'q' => $query,
            'apiKey' => $key,
            'sortBy' => 'publishedAt',
        ];

        // if ($category) {
        //     $params['category'] = $category;
        // }

        if ($date) {
            $params['from'] = $date;
        }

        return $this->makeRequest($url, $params);
    }

    public function fetchFromNYTimes($query, $category = null, $date = null)
    {
        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
        $params = [
            'q' => $query,
            'api-key' => 'CNAUaxHYkjU3gANmFNOAArl3Ao0NjUHo'//env('NYTIMES_KEY'),
        ];

        if ($category) {
            $params['fq'] = 'news_desk:("' . $category . '")';
        }

        if ($date) {
            $params['begin_date'] = $date;
        }

        return $this->makeRequest($url, $params);
    }

    private function makeRequest($url, $params)
    {
        try {
            //dd($params);
            $response = $this->client->request('GET', $url, [
                'query' => $params
            ]);
            // $response = $this->client->request('GET', $url, [
            //     'query' => [ $params ],
            // ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('API Request Error: ' . $e->getMessage());
            return null;
        }
    }
}
