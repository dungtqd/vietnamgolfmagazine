<?php

namespace App\Admin\Controllers;

use App\Models\LanguageModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ALanguageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý ngôn ngữ';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LanguageModel());
        $grid->column('name', __('Tên ngôn ngữ'));
        $grid->column('description', __('Mô tả'))->textarea();
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
        $show = new Show(LanguageModel::findOrFail($id));
        $show->field('name', __('Tên ngôn ngữ'));
        $show->field('description', __('Mô tả'));
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
        $form = new Form(new LanguageModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('language');
        }
        $form->text('name', __('Tên ngôn ngữ'))->required();
        $form->textarea('description', __('Mô tả'));

        return $form;
    }
}
