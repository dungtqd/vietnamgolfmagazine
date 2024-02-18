<?php

namespace App\Models\dto;

class ProductDto
{
    public $product;
    public $totalVote;

    public function __construct($product, $totalVote)
    {
        $this->product = $product;
        $this->totalVote = $totalVote;
    }
}