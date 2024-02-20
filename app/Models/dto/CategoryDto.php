<?php

namespace App\Models\dto;

class CategoryDto
{
    public $category;
    public $childrenCategory;

    public function __construct($category, $childrenCategory)
    {
        $this->category = $category;
        $this->childrenCategory = $childrenCategory;
    }
}