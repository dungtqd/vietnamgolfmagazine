<?php

namespace App\Admin\Controllers;

use App\Models\ExtensionModel;
use App\Models\LanguageModel;
use App\Models\ProvinceModel;
use App\Models\ZoneModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AExtensionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý Extension';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ExtensionModel());
        $grid->column('name', __('Tên extension'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('image', __('Hình ảnh'))->image();
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
        $show = new Show(ExtensionModel::findOrFail($id));
        $show->field('name', __('Tên extension'));
        $show->field('description', __('Mô tả'));
        $show->field('image', __('Hình ảnh'))->image();

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
        $form = new Form(new ExtensionModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('extension');
        }
        $form->text('name', __('Tên extension'))->required();
        $form->textarea('description', __('Mô tả'));
        $form->image('image', __('Hình ảnh'));

        return $form;
    }
}
