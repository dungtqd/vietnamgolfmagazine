<?php

namespace App\Http\Controllers;

use App\Models\dto\CategoryDto;
use App\Models\dto\ProgramDto;
use App\Models\ProgramModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
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


    public function getRootCategory($id): JsonResponse
    {
        //get list root program
        $rootCategory = DB::table('category as p')
            ->select('p.id',
                'p.name',
                'p.description',
                'p.language_id',
                'p.order',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', Constant::PARENT_ID_ROOT)
            ->where('p.language_id', '=', $id)
            ->orderBy('p.order', 'ASC')
            ->get();

        $totalProgram = $rootCategory->count();

        $rootCategoryDto = [];
        foreach ($rootCategory as $category) {
            //get children
            $children = $this->getChildrenByCategory($category->id, $id);

            $rootCategoryDto[] = new CategoryDto($category, $children);
        }

        $response = $this->_formatBaseResponseWithTotal(200, $rootCategoryDto, $totalProgram, 'Lấy dữ liệu thành công');

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
                'p.cover_image',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', Constant::PARENT_ID_ROOT)
            ->where('p.id', '=', $id)
            ->where('p.language_id', '=', $languageId)
            ->orderBy('p.updated_at', 'DESC')
            ->get();


        $rootProgramDto = [];
        foreach ($rootProgram as $program) {
            //count total vote
            $totalVote = $this->countTotalVotes($program->id, $languageId);
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
                'p.description',
                'p.language_id',
                'p.avatar_image',
                'p.cover_image',
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
            ->orderBy('p.updated_at', 'DESC')
            ->get();

        $totalProgram = $childrenProgram->count();

        $rootProgramDto = [];
        foreach ($childrenProgram as $childrenProgram) {
            //count total vote
            $totalVote = $this->countTotalChildrenVotes($childrenProgram->id, $languageId);
            $rootProgramDto[] = new ProgramDto($childrenProgram, $totalVote);
        }

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
                'p.cover_image',
                'p.start_date',
                'p.end_date',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.id', '=', $id)
            ->where('p.language_id', '=', $languageId)
            ->where('p.parent_id', '!=', Constant::PARENT_ID_ROOT)
            ->orderBy('p.updated_at', 'DESC')
            ->get();

        $response = $this->_formatBaseResponse(200, $childProgram, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    private function getChildrenByCategory($parent, $language): Collection
    {
        return DB::table('category as p')
            ->select('p.id',
                'p.name',
                'p.description',
                'p.language_id',
                'p.order',
                'p.created_at',
                'p.updated_at')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.parent_id', '=', $parent)
            ->where('p.language_id', '=', $language)
            ->orderBy('p.order', 'ASC')
            ->get();
    }

    private function countTotalChildrenVotes($parent, $langugage): int
    {
        $childrenProgram = DB::table('vote as v')
            ->select('v.id')
            ->join('program as p', 'v.program_id', '=', 'p.id')
            ->join('language as la', 'la.id', '=', 'p.language_id')
            ->where('p.id', '=', $parent)
            ->where('v.status', '=', Constant::VOTE_STATUS__VALID)
            ->where('p.language_id', '=', $langugage)
            ->get();

        return $childrenProgram->count();
    }


}
