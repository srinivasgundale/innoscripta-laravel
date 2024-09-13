<?php

namespace App\Http\Controllers;

use App\Services\News\NewYorkTimesService;
use App\Services\News\GuardianService;
use App\Services\News\NewsAPIService;
use Illuminate\Http\Request;
use Auth;

class NewsController extends Controller
{
    protected $newYorkTimesService;
    protected $guardianService;
    protected $newsAPIService;

    public function __construct(
        NewYorkTimesService $newYorkTimesService,
        GuardianService $guardianService,
        NewsAPIService $newsAPIService
    ) {
        $this->newYorkTimesService = $newYorkTimesService;
        $this->guardianService = $guardianService;
        $this->newsAPIService = $newsAPIService;
    }
    // public function search(Request $request)
    // {
    //     $keyword = $request->input('keyword');
    //     $guardianFilters = [
    //         'q' => $request->keyword,
    //         'from' => $request->from,
    //         'to-date' => $request->to,
    //         'section' => $request->category,
    //         'page-size' => $request->limit,
    //         'page' => $request->page,
    //     ];

    //     $nyTimesFilter = [
    //         'begin_date' => $request->from ? str_replace('-','',$request->from) : '',
    //         'end_date' => $request->to ? str_replace('-','',$request->to) : '',
    //         'fq' => 'section_name:("' . $request->category . '")',
    //         'page' => $request->page,
    //         'q' => $request->keyword,
    //     ];

    //     $newsAPIFilters = [
    //         'from' => $request->from,
    //         'to' => $request->to,
    //         'sources' => $request->source,
    //         'pageSize' => $request->limit,
    //         'q' => $request->keyword,
    //         'page' => $request->page
    //     ];
    //     $nyTimesResults = $this->newYorkTimesService->searchArticles($keyword, $nyTimesFilter);
    //     $guardianResults = $this->guardianService->searchArticles($keyword, $guardianFilters);
    //     $newsAPIResults = $this->newsAPIService->searchArticles($keyword, $newsAPIFilters);

    //     return response()->json([
    //         'nytimes' => $nyTimesResults,
    //         'guardian' => $guardianResults,
    //         'newsapi' => $newsAPIResults,
    //     ]);
    // }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $fromDate = $request->input('from');
        $toDate = $request->input('to');
        $category = $request->input('category');
        $page = $request->input('page', 0);
        $limit = $request->input('limit');
        $source = $request->input('source');
        $mainSource = $request->input('mainSource');

        // Guardian API Filters
        $guardianFilters = array_filter([
            'q' => $keyword,
            'from' => $fromDate,
            'to-date' => $toDate,
            'section' => $category,
            'page-size' => $limit,
            'page' => $page,
        ], function ($value) {
            return !empty($value);
        });

        // NYTimes API Filters
        $nyTimesFilters = array_filter([
            'begin_date' => $fromDate ? str_replace('-', '', $fromDate) : '',
            'end_date' => $toDate ? str_replace('-', '', $toDate) : '',
            //'fq' => $category ? 'section_name:("' . $category . '")' : null,
            'page' => $page,
            'q' => $keyword ?? 'a',
        ], function ($value) {
            return !empty($value);
        });

        // NewsAPI Filters
        $newsAPIFilters = array_filter([
            'from' => $fromDate,
            'to' => $toDate,
            'sources' => $source,
            'pageSize' => $limit,
            'q' => $keyword ?? 'a',
            'page' => $page,
        ], function ($value) {
            return !empty($value);
        });


        // Initialize result variables
        $nyTimesResults = [];
        $guardianResults = [];
        $newsAPIResults = [];

        // Fetch articles based on the main source
        if ($mainSource == 'All' || $mainSource == 'nytimes') {
            $nyTimesResults = $this->newYorkTimesService->searchArticles($nyTimesFilters);
        }

        if ($mainSource == 'All' || $mainSource == 'guardian') {
            $guardianResults = $this->guardianService->searchArticles($guardianFilters);
        }

        if ($mainSource == 'All' || $mainSource == 'newsapi') {
            $newsAPIResults = $this->newsAPIService->searchArticles($newsAPIFilters);
        }

        $mergedResults = array_merge(
            $this->formatNyTimesArticles($nyTimesResults),
            $this->formatGuardianArticles($guardianResults),
            $this->formatNewsAPIArticles($newsAPIResults)
        );
        shuffle($mergedResults);
        // return response()->json($mergedResults);

        return response()->json([
            'nytimes' => $nyTimesResults,
            'guardian' => $guardianResults,
            'newsapi' => $newsAPIResults,
            'articles' => $mergedResults
        ]);
    }
    private function formatNyTimesArticles($nyTimesResults)
    {
        return array_map(function ($article) {
            return [
                'headline' => $article['headline']['main'] ?? null,
                'url' => $article['web_url'] ?? null,
                'publish_date' => $article['pub_date'] ?? null,
                'image_url' => $this->getNyTimesImageUrl($article),
                'source' => 'The New York Times',
            ];
        }, $nyTimesResults);
    }

    private function getNyTimesImageUrl($article)
    {
        // Extract the first image from multimedia if available
        if (!empty($article['multimedia'])) {
            foreach ($article['multimedia'] as $media) {
                if ($media['subtype'] === 'xlarge') {
                    return 'https://www.nytimes.com/' . $media['url'];
                }
            }
        }
        return null;
    }
    private function formatGuardianArticles($guardianResults)
    {
        return array_map(function ($article) {
            return [
                'headline' => $article['webTitle'] ?? null,
                'url' => $article['webUrl'] ?? null,
                'publish_date' => $article['webPublicationDate'] ?? null,
                'image_url' => null, // Guardian API might not provide an image, adjust accordingly
                'source' => 'The Guardian',
            ];
        }, $guardianResults);
    }
    private function formatNewsAPIArticles($newsAPIResults)
    {
        return array_map(function ($article) {
            return [
                'headline' => $article['title'] ?? null,
                'url' => $article['url'] ?? null,
                'publish_date' => $article['publishedAt'] ?? null,
                'image_url' => $article['urlToImage'] ?? null,
                'source' => $article['source']['name'] ?? null,
            ];
        }, $newsAPIResults);
    }
}
