<?php

namespace App\Models\dto;

class BannerDto
{
    public $banner;
    public $detail;

    public function __construct($banner, $detail)
    {
        $this->banner = $banner;
        $this->detail = $detail;
    }
}