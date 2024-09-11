<?php
namespace App\Http\Controllers;

use App\Services\News\NewsServiceFactory;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $newsServiceFactory;

    public function __construct(NewsServiceFactory $newsServiceFactory)
    {
        $this->newsServiceFactory = $newsServiceFactory;
    }

    public function search(Request $request)
    {
        $source = $request->input('source');
        //dd($source);
        $keyword = $request->input('keyword');
        $filters = $request->only(['date', 'category']);

        $service = $this->newsServiceFactory->make($source);
        dd($service);
        $articles = $service->searchArticles($keyword, $filters);

        return response()->json($articles);
    }
}
