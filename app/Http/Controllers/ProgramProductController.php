<?php

namespace App\Http\Controllers;

use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramProductController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getByProgramCodeAndProductCode(Request $request): JsonResponse
    {
        $request->validate([
            'programCode' => 'required|string',
            'productCode' => 'required|string',
        ]);

        $programCode = $request->input('programCode');
        $productCode = $request->input('productCode');

        $programProduct = DB::table('program_product as p')
            ->select('p.id',
                'p.program_code',
                'p.product_code',
                'p.program_id',
                'p.product_id',
                'p.status',
                'p.order',
                'p.created_at',
                'p.updated_at')
            ->where('p.program_code', '=', $programCode)
            ->where('p.product_code', '=', $productCode)
            ->get();

        $response = $this->_formatBaseResponse(200, $programProduct, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
