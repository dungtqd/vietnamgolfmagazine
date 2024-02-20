<?php

namespace App\Admin\Controllers;

use App\Models\VoteModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AVoteController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý bình chọn';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VoteModel());
        $grid->column('program.name', __('Tên hạng mục'))->filter('like');
        $grid->column('product.name', __('Tên ứng viên'))->filter('like');
        $grid->column('user_id', __('Người bình chọn'))->filter('like');  //TODO: sau lay tu bang user
        $grid->column('ip', __('Địa chỉ IP'));
        $grid->column('agent', __('Agent'));
        $grid->column('language.name', __('Ngôn ngữ'));
//        $grid->column('status', __('Trạng thái'));  //todo: thêm convert status
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Vote", "Vote_Status","grid");
        });
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
        $show = new Show(VoteModel::findOrFail($id));
        $show->field('language.name', __('Ngôn ngữ'));
        $show->field('program.name', __('Tên hạng mục'));
        $show->field('product.name', __('Tên ứng viên'));
        $show->field('user_id', __('Người bình chọn'));  //TODO: sau lay tu bang user
        $show->field('ip', __('Địa chỉ IP'));
        $show->field('agent', __('Agent'));
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
        $programOptions = (new UtilsCommonHelper)->getAllPrograms(); //TODO: check ko cho chon product co parent_id=0
        $programDefault = $programOptions->keys()->first();

        $productOptions = UtilsCommonHelper::getAllProducts();
        $productDefault = $productOptions->keys()->first();

        $languageOptions = UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();


        $form = new Form(new VoteModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('vote');

            $programId = $form->model()->find($id)->getOriginal("program_id");
            $provinceId = $form->model()->find($id)->getOriginal("product_id");

            $form->select('program_id', __('Tên hạng mục'))->options($programOptions)->default($programId);
            $form->select('product_id', __('Tên ứng viên'))->options($productOptions)->default($provinceId);
        } else {
            $form->select('program_id', __('Tên hạng mục'))->options($programOptions)->required()->default($programDefault);
            $form->select('product_id', __('Tên ứng viên'))->options($productOptions)->required()->default($productDefault);
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
        }

        $form->text('user_id', __('Người bình chọn'));  //TODO: sau lay tu bang user
        $form->text('ip', __('Địa chỉ IP'));
        $form->text('agent', __('Agent'));
        $form->number('status', __('Trạng thái'));  //todo: thêm trạng thái từ bảng config

        return $form;
    }
}
