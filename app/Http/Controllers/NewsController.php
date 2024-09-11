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
        $page = $request->input('page', 1); // Default to page 1 if not provided
        $limit = $request->input('limit');
        $source = $request->input('source');

        $guardianFilters = array_filter([
            'q' => $keyword,
            'from' => $fromDate,
            'to-date' => $toDate,
            'section' => $category,
            'page-size' => $limit,
            'page' => $page,
        ]);

        $nyTimesFilters = array_filter([
            'begin_date' => $fromDate ? str_replace('-', '', $fromDate) : null,
            'end_date' => $toDate ? str_replace('-', '', $toDate) : null,
            'fq' => $category ? 'section_name:("' . $category . '")' : null,
            'page' => $page,
            'q' => $keyword,
        ]);

        $newsAPIFilters = array_filter([
            'from' => $fromDate,
            'to' => $toDate,
            'sources' => $source,
            'pageSize' => $limit,
            'q' => $keyword,
            'page' => $page,
        ]);

        $nyTimesResults = $this->newYorkTimesService->searchArticles($nyTimesFilters);
        $guardianResults = $this->guardianService->searchArticles($guardianFilters);
        $newsAPIResults = $this->newsAPIService->searchArticles($newsAPIFilters);

        return response()->json([
            'nytimes' => $nyTimesResults,
            'guardian' => $guardianResults,
            'newsapi' => $newsAPIResults,
        ]);
    }

}
