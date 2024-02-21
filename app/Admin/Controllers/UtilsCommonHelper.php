<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\CommonCodeModel;
use App\Models\LanguageModel;
use App\Models\LayoutModel;
use App\Models\ModuleModel;
use App\Models\ProductModel;
use App\Models\ProgramModel;
use App\Models\ProvinceModel;
use App\Models\VoteBannerModel;
use App\Models\ZoneModel;
use App\Util\Constant;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class UtilsCommonHelper
{
    public static function commonCode($group, $type, $description, $value)
    {
        return CommonCodeModel::where('group', $group)
            ->where('type', $type)
            ->pluck($description, $value);
    }

    public static function getAllPrograms(): Collection
    {
        return ProgramModel::all()->pluck('name', 'id');
    }

    public static function getExistProgramCode($originalProgramId, $languageId)
    {
        $program= ProgramModel::all()
            ->where('id', '=', $originalProgramId)
            ->where('language_id', '==', $languageId)->first();
        return $program->code;
    }
    public static function getOriginalProgram(): Collection
    {
        $language = LanguageModel::all()->where('code','=',Constant::LANGUAGE_DEFAULT)->first();
        return ProgramModel::all()
            ->where('language_id', '=', $language->id)
            ->pluck('name', 'id');
    }
    public static function getOriginalLanguage()
    {
        $language = LanguageModel::all()->where('code','=',Constant::LANGUAGE_DEFAULT)->first();
        return $language->id;
    }

    public static function getAllCategories(): Collection
    {
        return CategoryModel::all()->pluck('name', 'id');
    }

    public static function getAllChildrenCategories(): Collection
    {
        return CategoryModel::all()->where('parent_id', '!=', Constant::PARENT_ID_ROOT)->pluck('name', 'id');
    }

    public static function getAllProducts(): Collection
    {
        return ProductModel::all()->pluck('name', 'id');
    }

    public static function getAllLanguages(): Collection
    {
        return LanguageModel::all()->pluck('name', 'id');
    }

    public static function getAllProvinces(): Collection
    {
        return ProvinceModel::all()->pluck('name', 'id');
    }

    public static function getAllZones(): Collection
    {
        return ZoneModel::all()->pluck('name', 'id');
    }

    public static function getAllVoteBanners(): Collection
    {
        return VoteBannerModel::all()->pluck('name', 'id');
    }

    public static function getAllLayouts(): Collection
    {
        return LayoutModel::all()->pluck('name', 'id');
    }

    public static function getAllModules(): Collection
    {
        return ModuleModel::all()->pluck('name', 'id');
    }

    public static function commonCodeGridFormatter($group, $type, $description, $value)
    {
        $commonCode = CommonCodeModel::where('business_id', Admin::user()->business_id)
            ->where('group', $group)
            ->where('type', $type)
            ->where('value', $value)
            ->first();
        return $commonCode ? $commonCode->$description : '';
    }


    public static function findAllProduct()
    {
        return ProductModel::all()->where('status', 1)->pluck('name', 'id');
    }

    public static function optionsCategoryByProductGroupId($productGroupId)
    {
        if ($productGroupId !== null) {
            return CategoryModel::where("product_group_id", $productGroupId)->where('status', 1)->pluck('name', 'id');
        }
        return CategoryModel::all()->where('status', 1)->pluck('name', 'id');
    }

    //Kiem tra ten lai(doi lai)
    public static function statusFormatter($value, $group, $type, $isGrid): string
    {
        $result = $value ? $value : 0;

        $commonCode = CommonCodeModel::where('group', $group)
            ->where('type', $type)
            ->where('value', $result)
            ->first();
        if ($commonCode && $isGrid === "grid") {
//            if ($group === 'Vote') {
//                switch ($result) {
//                    case 0:
//                        $result = "<span class='label label-danger'>$commonCode->description_vi</span>";
//                        break;
//                    case 1:
//                        $result = "<span class='label label-warning'>$commonCode->description_vi</span>";
//                        break;
//                    case 2:
//                        $result = "<span class='label label-success'>$commonCode->description_vi</span>";
//                        break;
//                    case 3:
//                        $result = "<span class='label ' style='background-color: #97a0b3'>$commonCode->description_vi</span>";
//                        break;
//                }
//                return $result;
//            } else {
            return $result === 1 ? "<span class='label label-success'>$commonCode->description_vi</span>" : "<span class='label label-danger'>$commonCode->description_vi</span>";
//            }
        }


        return $commonCode->description_vi;
    }

    public static function percentFormatter($value, $isGrid)
    {

        if ($isGrid === "grid") {
            return $value === 0 ? "<span class='label label-infor' style='text-align: center;' >$value %</span>" : "<span class='label label-warning' style='text-align: center;' >$value %</span>";
        }
        return $value;
    }

    public static function statusFormFormatter()
    {
        return self::commonCode("Core", "Status", "description_vi", "value");
    }

    public static function statusGridFormatter($status)
    {
        return self::statusFormatter($status, "Core", "grid");
    }

    public static function statusDetailFormatter($status)
    {
        return self::statusFormatter($status, "Core", "detail");
    }


    public static function generateTransactionId($type)
    {
        $today = date("ymd");
        $currentTime = Carbon::now('Asia/Bangkok');
        $time = $currentTime->format('His');
        $userId = Str::padLeft(Admin::user()->id, 6, '0');
        $code = $type . $today . $userId . $time;
        return $code;
    }

    public static function generateCode(): UuidInterface
    {
        return Str::uuid();
    }

    public static function create_slug($string)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }


}
