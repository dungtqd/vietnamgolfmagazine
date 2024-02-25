<?php

namespace App\Http\Controllers;

use App\Models\PartnerModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\JsonResponse;

class PartnerController extends Controller
{
    use ResponseFormattingTrait;

    public function index(): JsonResponse
    {
        $partners = PartnerModel::all();
        $total = $partners->count();
        $response = $this->_formatBaseResponseWithTotal(200, $partners, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }


}
