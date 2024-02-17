<?php

namespace App\Http\Controllers;

use App\Models\dto\RootProgramDto;
use App\Models\ProgramModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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


    public function getRootProgram($id)
    {
        //get list root program
        $rootProgram = DB::table('program as p')
            ->select('p.id',
                'p.name',
                'p.description',
                'p.language_id',
                'la.name',
                'p.avatar_image',
                'p.cover_image',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', Constant::PROGRAM_ROOT)
            ->where('p.language_id', '=',$id)
            ->orderBy('p.updated_at', 'DESC')
            ->get();

        $totalProgram = $rootProgram->count();

        $rootProgramDto = [];
        foreach ($rootProgram as $rootProgram) {
            //count total vote
            $totalVote=$this->countTotalVotes($rootProgram->id, $id);
            $rootProgramDto[] = new RootProgramDto($rootProgram, $totalVote);
        }

        $response = $this->_formatBaseResponseWithTotal(200, $rootProgramDto, $totalProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    private function countTotalVotes($parent, $langugage)
    {
        $childrenProgram = DB::table('vote as v')
            ->select('p.id',
                'p.name')
            ->join('program as p', 'v.program_id', '=', 'p.id')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', $parent)
            ->where('v.status','=',Constant::VOTE_STATUS__VALID)
            ->where('p.language_id', '=',$langugage)
            ->get();

        return $childrenProgram->count();
    }
}
