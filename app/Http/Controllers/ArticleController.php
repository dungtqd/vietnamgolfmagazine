<?php

namespace App\Http\Controllers;

use App\Models\ArticleModel;
use App\Models\dto\CategoryDto;
use App\Models\dto\ProgramDto;
use App\Models\ProgramModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
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


    public function getAllByCategory(Request $request): JsonResponse
    {
        $request->validate([
            'categoryId' => 'required|integer',
            'languageId' => 'required|integer',
            'page' => 'integer|min:1',
            'size' => 'integer|min:1|max:10',
        ]);

        $categoryId = $request->input('categoryId');
        $languageId = $request->input('languageId');
        $page = $request->input('page', 1); // Default page is 1
        $size = $request->input('size', 10); // Default size is 10

        $articles = DB::table('article as a')
            ->select('a.id',
                'a.title',
                'a.slug',
                'a.description',
                'a.content',
                'a.image',
                'a.keywords',
                'a.like_count',
                'a.category_id',
                'a.created_at',
                'a.updated_at')
            ->join('category as c', 'c.id', '=', 'a.category_id')
            ->join('language as la', 'la.id', '=', 'a.language_id')
            ->where('c.id', '=', $categoryId)
            ->where('a.language_id', '=', $languageId)
            ->orderBy('a.updated_at', 'DESC')
            ->paginate($size, ['*'], 'page', $page); // Paginate with custom page size and page number


        $total = $articles->count();

        $response = $this->_formatBaseResponseWithTotal(200, $articles, $total, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getBySlug(Request $request): JsonResponse
    {
        $slug = $request->input('slug', '');
        $news=ArticleModel::where('slug', $slug)
            ->first();

        $response = $this->_formatBaseResponse(200, $news, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }




}
