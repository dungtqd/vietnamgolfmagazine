<?php

namespace App\Http\Controllers;

use App\Models\dto\BannerDto;
use App\Models\ExtensionModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExtensionController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
        $extension = ExtensionModel::all();
        $total = $extension->count();
        $response = $this->_formatBaseResponseWithTotal(200, $extension, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAll($languageId): JsonResponse
    {
        //get list root banner
        $rootBanner = DB::table('vote_banner as vb')
            ->select('vb.id',
                'vb.name as name',
                'vb.description',
                'vb.code',
                'vb.language_id',
                'vb.created_at',
                'vb.updated_at')
            ->join('language as la', 'la.id', '=', 'vb.language_id')
            ->where('vb.language_id', '=', $languageId)
            ->orderBy('vb.updated_at', 'DESC')
            ->get();

        $totalBanner = $rootBanner->count();

        $rootBannerDto = [];
        foreach ($rootBanner as $banner) {
            //detail of the banner
            $bannerDetail = $this->getDetailBanner($languageId, $banner->id);
            $rootBannerDto[] = new BannerDto($banner, $bannerDetail);
        }

        $response = $this->_formatBaseResponseWithTotal(200, $rootBannerDto, $totalBanner, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    private function getDetailBanner($languageId, $id): Collection
    {
        $rootBanner = DB::table('vote_banner_detail as vbd')
            ->select('vbd.id',
                'vbd.title',
                'vbd.description',
                'vbd.link',
                'vbd.language_id',
                'la.name',
                'vbd.desktop_image',
                'vbd.mobile_image',
                'vbd.order',
                'vbd.created_at',
                'vbd.updated_at')
            ->join('vote_banner as vb', 'vb.id', '=', 'vbd.banner_id')
            ->join('language as la', 'la.id', '=', 'vbd.language_id')
            ->where('vbd.banner_id', '=', $id)
            ->where('vbd.language_id', '=', $languageId)
            ->orderBy('vbd.order', 'ASC')
            ->get();

        return $rootBanner;
    }
}
