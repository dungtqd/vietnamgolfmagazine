<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ACategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý danh mục bài viết';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CategoryModel());
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('name', __('Tên danh mục'))->filter('like');
        $grid->column('parent.name', __('Danh mục cha'));
        $grid->column('order', __('Thứ tự'));

        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('updated_at', 'desc');
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
        $show = new Show(CategoryModel::findOrFail($id));
        $show->field('language.name', __('Ngôn ngữ'));
        $show->field('name', __('Tên danh mục'));
        $show->field('description', __('Mô tả'));
        $show->field('parent.name', __('Hạng mục cha'));
        $show->field('order', __('Thứ tự'));
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
        $categoryOptions = UtilsCommonHelper::getAllCategories();
        $categoryOptions->prepend('Không có','0');
        $categoryDefault = $categoryOptions->keys()->first();

        $languageOptions =  UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();

        $form = new Form(new CategoryModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('category');
            $parentId = $form->model()->find($id)->getOriginal("parent_id");

            $form->select('parent_id', __('Danh mục cha'))->options($categoryOptions)->default($parentId);
        } else {
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
            $form->select('parent_id', __('Hạng mục cha'))->options($categoryOptions)->required()->default($categoryDefault);
        }
        $form->text('name', __('Tên danh mục '))->required();
        $form->textarea('description', __('Mô tả'));
        $form->number('order', __('Thứ tự'))->required();

        return $form;
    }
}
