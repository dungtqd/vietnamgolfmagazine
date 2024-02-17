<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ProgramProductModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AProgramProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý hạng mục - ứng viên';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProgramProductModel());
        $grid->column('program.name', __('Tên hạng mục'))->filter('like');
        $grid->column('product.name', __('Tên ứng viên'))->filter('like');
        $grid->column('order', __('Sắp xếp'));
        $grid->column('status', __('Trạng thái'));  //todo: thêm convert status
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
        $show = new Show(ProgramProductModel::findOrFail($id));
        $show->field('program.name', __('Tên hạng mục'));
        $show->field('product.name', __('Tên ứng viên'));
        $show->field('order', __('Sắp xếp'));
        $show->field('status', __('Trạng thái'));  //todo: thêm format status

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
        $programDefault = $programOptions->keys()->first();

        $productOptions = (new UtilsCommonHelper)->getAllProducts();
        $productDefault = $productOptions->keys()->first();


        $form = new Form(new ProgramProductModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('program_product');

            $programId = $form->model()->find($id)->getOriginal("program_id");
            $provinceId = $form->model()->find($id)->getOriginal("product_id");

            $form->select('program_id', __('Tên hạng mục'))->options($programOptions)->default($programId);
            $form->select('product_id', __('Tên ứng viên'))->options($productOptions)->default($provinceId);  //TODO: nghiên cứu dùng multiSelect
        } else {
            $form->select('program_id', __('Tên hạng mục'))->options($programOptions)->required()->default($programDefault);
            $form->select('product_id', __('Tên ứng viên'))->options($productOptions)->required()->default($productDefault);
        }

        $form->number('order', __('Sắp xếp'));
        $form->number('status', __('Trạng thái'));  //todo: thêm trạng thái từ bảng config

        return $form;
    }
}
