<?php

namespace App\Models\dto;

class CategoryArticleDto
{
    public $category;
    public $article;

    public function __construct($category, $article)
    {
        $this->category = $category;
        $this->article = $article;
    }
}