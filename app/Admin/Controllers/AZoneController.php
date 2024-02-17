<?php

namespace App\Admin\Controllers;

use App\Models\LanguageModel;
use App\Models\ProvinceModel;
use App\Models\ZoneModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AZoneController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý vùng/miền';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ZoneModel());
        $grid->column('name', __('Tên vùng/miền'))->filter('like');
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
        $show = new Show(ZoneModel::findOrFail($id));
        $show->field('name', __('Tên vùng/miền'));
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
        $form = new Form(new ZoneModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('zone');
        }
        $form->text('name', __('Tên vùng/miền'))->required();
        $form->textarea('description', __('Mô tả'));

        return $form;
    }
}
