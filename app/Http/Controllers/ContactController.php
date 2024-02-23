<?php

namespace App\Http\Controllers;

use App\Models\ArticleModel;
use App\Models\ContactModel;
use App\Traits\ResponseFormattingTrait;
use App\Util\Constant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    use ResponseFormattingTrait;


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->input('email');
            $currentTimestamp = now()->timestamp;

            $contact = ContactModel::create([
                'email' => $validatedData['email'],
                'type' => Constant::CONTACT_TYPE__SUBCRIBE,
                'subscribe_status' => Constant::CONTACT_STATUS__SUBCESS,
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ]);
            if ($contact === null) {
                $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại.');
            } else {
                $response = $this->_formatBaseResponse(201, $contact, 'Tạo dữ liệu thành công.');
            }
            return $response;
        } catch (Exception $e) {
            return $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại.');
        }
    }

    public function storeMembership(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'phoneNumber' => 'required|string',
            ]);

            $currentTimestamp = now()->timestamp;

            $contact = ContactModel::create([
                'phone_number' => $validatedData['phoneNumber'],
                'type' => Constant::CONTACT_TYPE__MEMBERSHIP,
                'subscribe_status' => Constant::CONTACT_STATUS__SUBCESS,
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ]);
            if ($contact === null) {
                $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại.');
            } else {
                $response = $this->_formatBaseResponse(201, $contact, 'Tạo dữ liệu thành công.');
            }
            return $response;
        } catch (Exception $e) {
            return $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại1.');
        }
    }


    public function getAllByCategory(Request $request): JsonResponse
    {
        $request->validate([
            'categoryId' => 'required|integer',
            'languageId' => 'required|integer',
            'page' => 'integer|min:1',
            'size' => 'integer|min:1|max:100',
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

    public function getLatestArticleByCategory(Request $request): JsonResponse
    {
        $request->validate([
            'categoryId' => 'required|integer',
            'languageId' => 'required|integer',
            'page' => 'integer|min:1',
            'size' => 'integer|min:1|max:100',
        ]);

        $categoryId = $request->input('categoryId');
        $languageId = $request->input('languageId');
        $page = $request->input('page', 1); // Default page is 1
        $size = $request->input('size', 10); // Default size is 10

        $articles = DB::table('article as a')
            ->select('a.id',
                'a.title',
                'c.id as category_id',
                'c.name as category_name',
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
            ->where('c.parent_id', '=', $categoryId)
            ->where('a.language_id', '=', $languageId)
            ->orderBy('a.updated_at', 'DESC')
            ->paginate($size, ['*'], 'page', $page);
//        error_log($articles->data);
        $total = $articles->count();
        $response = $this->_formatBaseResponse(200, $articles, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

    public function getBySlug(Request $request): JsonResponse
    {
        $slug = $request->input('slug', '');
        $news = ArticleModel::where('slug', $slug)
            ->first();

//        $relateCategory=CategoryModel::all()->where("id", "=", $news->category_id);
        $response = $this->_formatBaseResponse(200, $news, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
//    public function getArticleInSameCategory(Request $request): JsonResponse
//    {
//        $request->validate([
//            'categoryId' => 'required|integer',
//            'languageId' => 'required|integer',
//            'page' => 'integer|min:1',
//            'size' => 'integer|min:1|max:100',
//        ]);
//
//        $categoryId = $request->input('categoryId');
//        $languageId = $request->input('languageId');
//        $page = $request->input('page', 1); // Default page is 1
//        $size = $request->input('size', 10); // Default size is 10
//
//        $article=DB::table('article as a')
//            ->select('a.id',
//                'a.title',
//                'c.id as category_id',
//                'c.name as category_name',
//                'a.slug',
//                'a.description',
//                'a.content',
//                'a.image',
//                'a.keywords',
//                'a.like_count',
//                'a.category_id',
//                'a.created_at',
//                'a.updated_at')
//            ->join('category as c', 'c.id', '=', 'a.category_id')
//            ->join('language as la', 'la.id', '=', 'a.language_id')
//            ->where('c.id', '=', $categoryId)
//            ->where('a.language_id', '=', $languageId)
//            ->orderBy('a.updated_at', 'DESC')
//            ->paginate($size, ['*'], 'page', $page);
//
//        $response = $this->_formatBaseResponse(200, $article, 'Lấy dữ liệu thành công');
//        return response()->json($response);
//    }


}
