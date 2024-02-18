<?php

namespace App\Http\Controllers;

use App\Models\LanguageModel;
use App\Models\VoteModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'program_id' => 'required|integer',
            'product_id' => 'required|integer',
            'language_id' => 'required|integer',
            'ip' => 'required|string|max:255',
            'agent' => 'required|string|max:255',
        ]);

        $programId = $request->input('program_id');
        $productId = $request->input('product_id');
        $languageId = $request->input('language_id');
        $ip = $request->input('ip');

        //check program and product
        $productAndProgram = $this->countValidProgramAndProduct($programId, $productId);
        if ($productAndProgram === 0) {
            $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại');
        } else {
            //check language
            $language = LanguageModel::find($languageId);
            if (is_null($language)) {
                $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại do ngôn ngữ không xác định');
            } else {
                //check trung ip
                $existIP = DB::table('vote as vo')
                    ->select('vo.id')
                    ->where('vo.program_id', '=', $programId)
                    ->where('vo.product_id', '=', $productId)
                    ->where('vo.ip', '=', $ip)
                    ->get();
                error_log($existIP);

                $countExist = $existIP->count();
                if ($countExist !== 0) {
                    $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại do trùng IP');
                } else {
                    $currentTimestamp = now()->timestamp;
                    $vote = VoteModel::create([
                        'program_id' => $validatedData['program_id'],
                        'product_id' => $validatedData['product_id'],
                        'language_id' => $validatedData['language_id'],
                        'ip' => $validatedData['ip'],
                        'agent' => $validatedData['agent'],
                        'status' => Constant::VOTE_STATUS__VALID,
                        'created_at' => $currentTimestamp,
                        'updated_at' => $currentTimestamp,
                    ]);
                    if ($vote === null) {
                        $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại');
                    } else {
                        $response = $this->_formatBaseResponse(201, $vote, 'Tạo dữ liệu thành công');
                    }
                }

            }

        }
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

    public function countTotalVote(): JsonResponse
    {
        $vote = DB::table('vote as vo')
            ->select('vo.id')
            ->where('vo.status', '=', Constant::VOTE_STATUS__VALID)
            ->get();
        $total = $vote->count();

        $response = $this->_formatBaseResponseWithTotal(200, [], $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function countVoteByProgram(Request $request): JsonResponse
    {
        $request->validate([
            'programId' => 'required|integer',
            'languageId' => 'required|integer',
        ]);

        $programId = $request->input('programId');
        $languageId = $request->input('languageId');

        $vote = DB::table('vote as vo')
            ->select('vo.id')
            ->join('program as p', 'p.id', '=', 'vo.program_id')
            ->where('vo.program_id', '=', $programId)
            ->where('vo.status', '=', Constant::VOTE_STATUS__VALID)
            ->where('p.language_id', '=', $languageId)
            ->get();
        $total = $vote->count();

        $response = $this->_formatBaseResponseWithTotal(200, [], $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function countVoteByProgramAndProduct(Request $request): JsonResponse
    {
        $request->validate([
            'programId' => 'required|integer',
            'languageId' => 'required|integer',
            'productId' => 'required|integer',
        ]);

        $programId = $request->input('programId');
        $productId = $request->input('productId');
        $languageId = $request->input('languageId');

        $vote = DB::table('vote as vo')
            ->select('vo.id')
            ->join('program as p', 'p.id', '=', 'vo.program_id')
            ->join('product as po', 'po.id', '=', 'vo.product_id')
            ->where('vo.program_id', '=', $programId)
            ->where('vo.product_id', '=', $productId)
            ->where('vo.status', '=', Constant::VOTE_STATUS__VALID)
            ->where('p.language_id', '=', $languageId)
            ->get();
        $total = $vote->count();

        $response = $this->_formatBaseResponseWithTotal(200, [], $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    private function countValidProgramAndProduct($programId, $productId): int
    {
        $productAndProgram = DB::table('program_product as pp')
            ->select('pp.id')
            ->where('pp.program_id', '=', $programId)
            ->where('pp.product_id', '=', $productId)
            ->where('pp.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
            ->get();
        error_log($productAndProgram);
        return $productAndProgram->count();
    }
}
