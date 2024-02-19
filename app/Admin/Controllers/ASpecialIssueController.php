<?php

namespace App\Admin\Controllers;

use App\Models\ArticleModel;
use App\Models\SpecialIssueModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ASpecialIssueController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý đặc san';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new SpecialIssueModel());
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('title', __('Tiêu đề'))->filter('like');
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('public_date', __('Ngày phát hành'));
        $grid->column('number', __('Số phát hành'));
        $grid->column('link', __('Đường dẫn'));


        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('updated_at', 'desc');
//        $grid->fixColumns(0, -1);
        $grid->disableFilter();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id): Show
    {
        $show = new Show(SpecialIssueModel::findOrFail($id));
        $show->field('language.name', __('Ngôn ngữ'));
//        $show->divider();
        $show->field('title', __('Tiêu đề'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('public_date', __('Ngày phát hành'));
        $show->field('number', __('Số phát hành'));
        $show->field('link', __('Đường dẫn'));

        $show->field('created_at', __('Ngày tạo'));
        $show->field('updated_at', __('Ngày cập nhật'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $languageOptions = UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();

        $form = new Form(new SpecialIssueModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('special_issue');
        } else {
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
        }
        $form->text('title', __('Tiêu đề'))->required();
        $form->image('image', __('Hình ảnh'));
        $form->date('public_date', __('Ngày phát hành'))->required();
        $form->number('number', __('Số phát hành'))->required();
        $form->text('link', __('Đường dẫn'))->required();

        return $form;
    }
}
