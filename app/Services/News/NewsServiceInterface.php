<?php

namespace App\Services\News;

interface NewsServiceInterface
{
    public function searchArticles(array $filters): array;
}
