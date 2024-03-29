<?php

namespace App\Http\Controllers;

use App\Models\dto\ProgramDto;
use App\Models\ProgramModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $program = ProgramModel::all();
        $total = $program->count();
        $response = $this->_formatBaseResponseWithTotal(200, $program, $total, 'Lấy dữ liệu thành công');
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
     * @return JsonResponse
     */
    public function show($id)
    {
        $program = ProgramModel::find($id);
        $response = $this->_formatBaseResponse(200, $program, 'Lấy dữ liệu thành công');
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


    public function getRootProgram($id): JsonResponse
    {
        //get list root program
        $rootProgram = DB::table('program as p')
            ->select('p.id',
                'p.name',
                'p.code',
                'p.description',
                'p.language_id',
                'p.avatar_image',
//                'p.cover_image',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', Constant::PARENT_ID_ROOT)
            ->where('p.language_id', '=', $id)
            ->orderBy('p.order', 'ASC')
            ->get();

        $totalProgram = $rootProgram->count();

        $rootProgramDto = [];
        foreach ($rootProgram as $rootProgram) {
            //count total vote
            $totalVote = $this->countTotalVotes($rootProgram->id);
            $rootProgramDto[] = new ProgramDto($rootProgram, $totalVote);
        }

        $response = $this->_formatBaseResponseWithTotal(200, $rootProgramDto, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getDetailRootProgram(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'languageId' => 'required|integer',
        ]);

        $id = $request->input('id');
        $languageId = $request->input('languageId');

        $rootProgram = DB::table('program as p')
            ->select('p.id',
                'p.name',
                'p.description',
                'p.language_id',
                'p.avatar_image',
//                'p.cover_image',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', Constant::PARENT_ID_ROOT)
            ->where('p.id', '=', $id)
            ->where('p.language_id', '=', $languageId)
            ->orderBy('p.order', 'ASC')
            ->get();


        $rootProgramDto = [];
        foreach ($rootProgram as $program) {
            //count total vote
            $totalVote = $this->countTotalVotes($program->id);
            $rootProgramDto[] = new ProgramDto($rootProgram, $totalVote);
        }

        $response = $this->_formatBaseResponse(200, $rootProgramDto, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getChildrenProgram(Request $request): JsonResponse
    {
        $request->validate([
            'parentId' => 'required|integer',
            'languageId' => 'required|integer',
        ]);

        $parentId = $request->input('parentId');
        $languageId = $request->input('languageId');

        //get list children program
        $childrenProgram = DB::table('program as p')
            ->select('p.id',
                'p.name',
                'p.code',
                'p.description',
                'p.language_id',
                'p.avatar_image',
//                'p.cover_image',
                'p.seo_title',
                'p.meta_keyword',
                'p.seo_url',
                'p.seo_title',
                'p.meta_description',
                'p.robots_tag',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', $parentId)
            ->where('p.language_id', '=', $languageId)
            ->orderBy('p.order', 'ASC')
            ->get();

        $totalProgram = $childrenProgram->count();

        $rootProgramDto = [];
        foreach ($childrenProgram as $childrenProgram) {
            //count total vote
            $totalVote = $this->countTotalChildrenVotes($childrenProgram->code);
            $rootProgramDto[] = new ProgramDto($childrenProgram, $totalVote);
        }

        $collection = collect($rootProgramDto);
        $response = $this->_formatBaseResponseWithTotal(200, $rootProgramDto, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getDetailChildProgram(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'languageId' => 'required|integer',
        ]);

        $id = $request->input('id');
        $languageId = $request->input('languageId');

        $childProgram = DB::table('program as p')
            ->select('p.id',
                'p.name',
                'p.description',
                'p.language_id',
                'p.avatar_image',
//                'p.cover_image',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.id', '=', $id)
            ->where('p.language_id', '=', $languageId)
            ->where('p.parent_id', '!=', Constant::PARENT_ID_ROOT)
            ->orderBy('p.order', 'ASC')
            ->get();

        $response = $this->_formatBaseResponse(200, $childProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    private function countTotalVotes($parent): int
    {
        $childrenProgram = DB::table('vote as v')
            ->select('v.id')
            ->join('program as p', 'v.program_id', '=', 'p.id')
            ->where('p.parent_id', '=', $parent)
            ->where('v.status', '=', Constant::VOTE_STATUS__VALID)
            ->get();

        return $childrenProgram->count();
    }

    private function countTotalChildrenVotes($programCode): int
    {
        $childrenProgram = DB::table('vote as v')
            ->select('v.id')
            ->join('program_product as p', 'v.program_product_id', '=', 'p.id')
//            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.status', '=', Constant::PROGRAM_PRODUCT_STATUS__ACTIVE)
            ->where('v.status', '=', Constant::VOTE_STATUS__VALID)
            ->where('p.program_code', '=', $programCode)
//            ->where('p.language_id', '=', $langugage)
            ->get();

        return $childrenProgram->count();
    }


}
