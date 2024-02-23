<?php

namespace App\Http\Controllers;

use App\Models\ArticleModel;
use App\Models\ContactModel;
use App\Models\PartnerModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
