<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Models\dto\ProductDto;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
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
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
    }

    public function getRankByProgram(Request $request): JsonResponse
    {
        $request->validate([
            'programCode' => 'required|string',
            'languageId' => 'required|integer',
        ]);


        $programCode = $request->input('programCode');
        $languageId = $request->input('languageId');

        //get list children program
        $childrenProduct = DB::table('product as p')
            ->select('p.id',
                'p.name',
                'p.code',
                'p.description',
                'p.language_id',
                'p.image',
                'p.image1',
                'p.image2',
                'p.image3')
            ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
//            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
            ->where('pp.program_code', '=', $programCode)
            ->where('p.language_id', '=', $languageId)
            ->orderBy('p.updated_at', 'DESC')
            ->get();

        $totalProgram = $childrenProduct->count();


        $rootProductDto = [];
        foreach ($childrenProduct as $childrenProduct) {
            //find program
            $programProduct = UtilsCommonHelper::getProgramProductByCode($programCode, $childrenProduct->code);

            //count total vote
            $totalVote = $this->countVoteByProgramAndProduct($programProduct->id);
            $rootProductDto[] = new ProductDto($childrenProduct, $totalVote);
        }

        //sort
        $collection = collect($rootProductDto);
        $sortedCollection = $collection->sortByDesc('totalVote');

        $response = $this->_formatBaseResponseWithTotal(200, $rootProductDto, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    private function countVoteByProgramAndProduct($programProductId): int
    {
        $vote = DB::table('vote as vo')
            ->select('vo.id')
            ->join('program_product as pp', 'pp.id', '=', 'vo.program_product_id')
            ->where('vo.status', '=', Constant::VOTE_STATUS__VALID)
            ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
            ->where('pp.id', '=', $programProductId)
            ->get();
        return $vote->count();
    }

    public function getAllByProgram(Request $request): JsonResponse
    {
        $request->validate([
            'programCode' => 'required|string',
            'languageId' => 'required|integer',
            'productName' => 'nullable|string',
        ]);

        $perPage = $request->input('limit', 16);

        $programCode = $request->input('programCode');
        $languageId = $request->input('languageId');
        $productName = $request->input('productName');

        //get list children program
        if (is_null($productName)) {
            $childrenProduct = DB::table('product as p')
                ->select('p.id',
                    'p.name',
                    'p.code',
                    'p.language_id',
                    'p.image',
                    'p.image1',
                    'p.image2',
                    'p.image3',
                    'p.description')
                ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
                ->where('pp.program_code', '=', $programCode)
                ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
                ->where('p.language_id', '=', $languageId)
                ->orderBy('pp.order', 'ASC')
                ->limit($perPage)
                ->get();
        } else {
            $childrenProduct = DB::table('product as p')
                ->select('p.id',
                    'p.name',
                    'p.language_id',
                    'p.image',
                    'p.image1',
                    'p.image2',
                    'p.image3',
                    'p.description')
                ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
                ->where('pp.program_code', '=', $programCode)
                ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
                ->where('p.name', 'like', '%' . $productName . '%')
                ->where('p.language_id', '=', $languageId)
                ->orderBy('pp.order', 'ASC')
//                ->orderBy('p.name', 'ASC')
                ->limit($perPage)
                ->get();
        }

        $totalProgram = $childrenProduct->count();

        $response = $this->_formatBaseResponseWithTotal(200, $childrenProduct, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getDetailById(Request $request): JsonResponse
    {
        $request->validate([
//            'programId' => 'required|integer',
            'languageId' => 'required|integer',
            'productId' => 'required|integer',
        ]);

//        $programId = $request->input('programId');
        $languageId = $request->input('languageId');
        $productId = $request->input('productId');

        $childrenProduct = DB::table('product as p')
            ->select('p.id',
                'p.name',
                'p.code',
                'p.image',
                'p.image1',
                'p.image2',
                'p.image3',
                'p.language_id',
                'p.description')
            ->where('p.id', '=', $productId)
            ->where('p.language_id', '=', $languageId)
            ->get();

        $response = $this->_formatBaseResponse(200, $childrenProduct, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
