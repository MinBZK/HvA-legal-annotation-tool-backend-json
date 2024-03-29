<?php

declare(strict_types=1);

namespace App\Factories;

use App\Contracts\Factories\ArticleFactoryInterface;
use App\Models\Article;
use App\Models\Law;

class ArticleFactory implements ArticleFactoryInterface
{
    public function create(Law $law, string $title, string $text): Article
    {
        $article = new Article();

        $article->title = $title;
        $article->text = $text;

        $article->law()->associate($law);

        $article->save();

        return $article;
    }
}
