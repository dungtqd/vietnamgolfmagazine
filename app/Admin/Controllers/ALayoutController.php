<?php

namespace App\Admin\Controllers;

use App\Models\LayoutModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ALayoutController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý layout';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LayoutModel());
        $grid->column('name', __('Tên layout'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('router', __('Đường dẫn'));

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
        $show = new Show(LayoutModel::findOrFail($id));
        $show->field('name', __('Tên layout'));
        $show->field('description', __('Mô tả'));
        $show->field('router', __('Đường dẫn'));

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
        $form = new Form(new LayoutModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('layout');
        }
        $form->text('name', __('Tên layout'))->required();
        $form->textarea('description', __('Mô tả'));
        $form->text('router', __('Đường dẫn'));

        return $form;
    }
}
