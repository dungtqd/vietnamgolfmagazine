<?php

namespace App\Http\Controllers;

use App\Models\AlertModel;
use App\Models\dto\ProductDto;
use App\Models\ZoneModel;
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
        $alerts = AlertModel::all();
        $total = $alerts->count();
        $response = $this->_formatBaseResponseWithTotal(200, $alerts, $total, 'Lấy dữ liệu thành công');
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
        $alert = AlertModel::create($request->all());
        $response = $this->_formatBaseResponse(201, $alert, 'Tạo mới cảnh báo thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = AlertModel::find($id);
        $response = $this->_formatBaseResponse(200, $alert, 'Lấy dữ liệu thành công');
        return response()->json($response);
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
        $alert = AlertModel::findOrFail($id);
        $alert->update($request->all());
        $response = $this->_formatBaseResponse(200, $alert, 'Cập nhật dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $alert = AlertModel::findOrFail($id);
            $alert->delete();
        } catch (Exception $e) {
            $response = $this->_formatBaseResponse(400, $e, "Xoá dữ liệu thất bại");
            return response()->json($response);
        }
        $response = $this->_formatBaseResponse(204, $alert, 'Xoá dữ liệu thành công');
        return response()->json($response);
    }

    public function getRankByProgram(Request $request): JsonResponse
    {
        $request->validate([
            'programId' => 'required|integer',
            'languageId' => 'required|integer',
        ]);


        $programId = $request->input('programId');
        $languageId = $request->input('languageId');

        //get list children program
        $childrenProduct = DB::table('product as p')
            ->select('p.id',
                'p.name',
                'p.description',
                'p.language_id',
                'la.name',
                'p.image')
            ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('pp.program_id', '=', $programId)
            ->where('p.language_id', '=', $languageId)
            ->orderBy('p.updated_at', 'DESC')
            ->get();

        $totalProgram = $childrenProduct->count();


        $rootProductDto = [];
        foreach ($childrenProduct as $childrenProduct) {
            //count total vote
            $totalVote = $this->countVoteByProgramAndProduct($programId, $childrenProduct->id, $languageId);
            $rootProductDto[] = new ProductDto($childrenProduct, $totalVote);
        }

        //sort
        $collection = collect($rootProductDto);
        $sortedCollection = $collection->sortByDesc('totalVote');

        $response = $this->_formatBaseResponseWithTotal(200, $sortedCollection, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    private function countVoteByProgramAndProduct($programId, $productId, $languageId): int
    {
        $vote = DB::table('vote as vo')
            ->select('vo.id')
            ->where('vo.program_id', '=', $programId)
            ->where('vo.product_id', '=', $productId)
            ->where('vo.status', '=', Constant::VOTE_STATUS__VALID)
            ->get();
        return $vote->count();
    }

    public function getAllByProgram(Request $request): JsonResponse
    {
        $request->validate([
            'programId' => 'required|integer',
            'languageId' => 'required|integer',
            'productName' => 'nullable|string',
        ]);

        $perPage = $request->input('limit', 16);

        $programId = $request->input('programId');
        $languageId = $request->input('languageId');
        $productName = $request->input('productName');

        //get list children program
        if (is_null($productName)) {
            $childrenProduct = DB::table('product as p')
                ->select('p.id',
                    'p.name',
                    'p.language_id',
                    'p.description')
                ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
                ->join('program as po', 'po.id', '=', 'pp.program_id')
                ->where('po.id', '=', $programId)
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
                    'p.description')
                ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
                ->join('program as po', 'po.id', '=', 'pp.program_id')
                ->where('po.id', '=', $programId)
                ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
                ->where('p.name', 'like', '%' . $productName . '%')
                ->where('p.language_id', '=', $languageId)
                ->orderBy('pp.order', 'ASC')
                ->limit($perPage)
                ->get();
        }

        $totalProgram = $childrenProduct->count();

        $response = $this->_formatBaseResponseWithTotal(200, $childrenProduct, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getDetailByIdAndProgram(Request $request): JsonResponse
    {
        $request->validate([
            'programId' => 'required|integer',
            'languageId' => 'required|integer',
            'productId' => 'required|integer',
        ]);

        $programId = $request->input('programId');
        $languageId = $request->input('languageId');
        $productId = $request->input('productId');

        $childrenProduct = DB::table('product as p')
            ->select('p.id',
                'p.name',
                'p.language_id',
                'p.description')
            ->join('program_product as pp', 'p.id', '=', 'pp.product_id')
            ->join('program as po', 'po.id', '=', 'pp.program_id')
            ->where('po.id', '=', $programId)
            ->where('p.id', '=', $productId)
            ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
            ->where('p.language_id', '=', $languageId)
            ->orderBy('pp.order', 'ASC')
            ->get();

        $response = $this->_formatBaseResponse(200, $childrenProduct, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
