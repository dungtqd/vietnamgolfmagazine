<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
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
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('code', __('Mã code'));
        $grid->column('order', __('Sắp xếp'));
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
        $grid->model()->orderBy('created_at', 'desc');
        $grid->fixColumns(0, -1);
        $grid->disableFilter();
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
        $show->field('code', __('Mã code'));
        $show->field('order', __('Order'));
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
        $programOptions = (new UtilsCommonHelper)->getAllPrograms();
        $programOptions->prepend('Không có','0');
        $programDefault = $programOptions->keys()->first();

        $languageOptions = (new UtilsCommonHelper)->getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();

        $provinceOptions = (new UtilsCommonHelper)->getAllProvinces();
        $provinceDefault = $provinceOptions->keys()->first();

        $zoneOptions = (new UtilsCommonHelper)->getAllZones();
        $zoneDefault = $zoneOptions->keys()->first();


        $form = new Form(new ProductModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('product');

//            $zoneId = $form->model()->find($id)->getOriginal("zone_id");
//            $provinceId = $form->model()->find($id)->getOriginal("province_id");

//            $form->select('zone_id', __('Vùng/miền'))->options($zoneOptions)->default($zoneId);
//            $form->select('province_id', __('Tỉnh/thành phố'))->options($provinceOptions)->default($provinceId);
            $form->text('code', __('Mã code'))->disable();
        } else {
//            $form->select('zone_id', __('Vùng/miền'))->options($zoneOptions)->required()->default($zoneDefault);
//            $form->select('province_id', __('Tỉnh/thành phố'))->options($provinceOptions)->required()->default($provinceDefault);
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
            $form->text('code', __('Mã code'));
        }
        $form->text('name', __('Tên ứng viên'))->required();
        $form->textarea('description', __('Mô tả'));
        $form->image('image', __('Ảnh'));
        $form->number('order', __('Order'));
//        $form->text('location', __('Địa chỉ'));
//        $form->text('website', __('Website'));
//        $form->mobile('phone_number', __('Số điện thoại'));
//        $form->number('holes', __('Tổng số hố'));

        return $form;
    }
}
