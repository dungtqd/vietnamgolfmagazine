<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Util\Constant;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý ứng viên';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductModel());
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('name', __('Tên ứng viên'))->filter('like');
        $grid->column('originalProduct.name', __('Ứng viên gốc theo tiếng Việt'))->filter('like');
        $grid->column('code', __('Mã code'));
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('image1', __('Hình ảnh 1'))->image();
        $grid->column('image2', __('Hình ảnh 2'))->image();
        $grid->column('image3', __('Hình ảnh 3'))->image();
//        $grid->column('order', __('Sắp xếp'));
//        $grid->column('location', __('Địa chỉ'));
//        $grid->column('website', __('Website'));
//        $grid->column('phone_number', __('Số điện thoại'));
//        $grid->column('holes', __('Tổng số hố'));
//        $grid->column('zone.name', __('Vùng'));
//        $grid->column('province.name', __('Tỉnh/Thành phố'));
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('language_id', 'asc');
        $grid->model()->orderBy('created_at', 'desc');
        $grid->fixColumns(0, -1);
//        $grid->disableFilter();
        $grid-> filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $languageOptions = UtilsCommonHelper::getAllLanguages();
            $programOptions = UtilsCommonHelper::getOriginalProduct();
            $programOptions->prepend('Không có', '0');
            $filter->equal('language_id', 'Ngôn ngữ')->select($languageOptions);
            $filter->equal('original_product', 'Ứng viên gốc theo tiếng Việt')->select($programOptions);
            $filter->like('name', 'Tên ứng viên');
            $filter->date('created_at', 'Ngày tạo');
            $filter->date('updated_at', 'Ngày cập nhật');
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductModel::findOrFail($id));
        $show->field('name', __('Tên ứng viên'));
        $show->field('description', __('Mô tả'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('image1', __('Hình ảnh1'))->image();
        $show->field('image2', __('Hình ảnh2'))->image();
        $show->field('image3', __('Hình ảnh3'))->image();
        $show->field('code', __('Mã code'));
//        $show->field('order', __('Order'));
//        $show->field('location', __('Địa chỉ'));
//        $show->field('website', __('Website'));
//        $show->field('phone_number', __('Số điện thoại'));
//        $show->field('holes', __('Tổng số hố'));
//        $show->field('zone.name', __('Vùng'));
//        $show->field('province.name', __('Tỉnh/Thành phố'));

        $show->field('created_at', __('Ngày tạo'));
        $show->field('updated_at', __('Ngày cập nhật'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $languageOptions = UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();
//
//        $provinceOptions = (new UtilsCommonHelper)->getAllProvinces();
//        $provinceDefault = $provinceOptions->keys()->first();
//
//        $zoneOptions = (new UtilsCommonHelper)->getAllZones();
//        $zoneDefault = $zoneOptions->keys()->first();

        //ngôn ngữ default lúc tạo là tiếng Việt
        $originalProductOptions = UtilsCommonHelper::getOriginalProduct();
        $originalProductOptions->prepend('Không có', '0');
        $originalProductDefault = $originalProductOptions->keys()->first();

        $languageOptions = UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();


        $form = new Form(new ProductModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('product');

//            $zoneId = $form->model()->find($id)->getOriginal("zone_id");
//            $provinceId = $form->model()->find($id)->getOriginal("province_id");

//            $form->select('zone_id', __('Vùng/miền'))->options($zoneOptions)->default($zoneId);
//            $form->select('province_id', __('Tỉnh/thành phố'))->options($provinceOptions)->default($provinceId);

            $languageId = $form->model()->find($id)->getOriginal("language_id");
            $originalProductId = $form->model()->find($id)->getOriginal("original_product");


//            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->default($languageId)->disable()->value($languageId);
            $form->select('original_product', __('Ứng viên gốc theo tiếng Việt'))->options($originalProductOptions)->default($originalProductId)->disable()->value($originalProductId);
            $form->saving(function ($form) {
                $languageId = $form->language_id;
                error_log($languageId);
//                $form->language_id=$languageId;
            });
            $form->text('code', __('Mã code'))->disable()->required();
        } else {
//            $form->select('zone_id', __('Vùng/miền'))->options($zoneOptions)->required()->default($zoneDefault);
//            $form->select('province_id', __('Tỉnh/thành phố'))->options($provinceOptions)->required()->default($provinceDefault);
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
            $form->select('original_product', __('Ứng viên gốc theo tiếng Việt'))->options($originalProductOptions)->required()->default($originalProductDefault);

            $form->hidden('code', __('Mã code'));

            $form->saving(function ($form) {
                $originalProductId = $form->original_product;
                $intOriginalProductId= (int)$originalProductId;

                $languageId = $form->language_id;
                $intLanguageId= (int)$languageId;
                $languageDefault = UtilsCommonHelper::getOriginalLanguage();
                //check neu ngon ngu goc + original_product =0 thi tao ma code
                if ($intLanguageId === $languageDefault && $intOriginalProductId === Constant::PARENT_ID_ROOT) {
                    //tao moi ma code
                    error_log("tao moi ma code");
                    $form->code = UtilsCommonHelper::generateCode();
                }else{
                    //tim ma code cu
                    $existCode=UtilsCommonHelper::getExistProductCode($originalProductId, $languageDefault);
                    error_log("lay lai code cu");
                    error_log($existCode);
                    $form->code =$existCode;
                }
            });
        }
        $form->text('name', __('Tên ứng viên'))->required();
        $form->textarea('description', __('Mô tả'));
        $form->image('image', __('Hình ảnh'));
        $form->image('image1', __('Hình ảnh1'));
        $form->image('image2', __('Hình ảnh2'));
        $form->image('image3', __('Hình ảnh3'));
//        $form->number('order', __('Sắp xếp'));
//        $form->text('location', __('Địa chỉ'));
//        $form->text('website', __('Website'));
//        $form->mobile('phone_number', __('Số điện thoại'));
//        $form->number('holes', __('Tổng số hố'));

        return $form;
    }
}
