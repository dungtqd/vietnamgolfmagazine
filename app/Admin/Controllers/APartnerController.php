<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\PartnerModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class APartnerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý đối tác';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PartnerModel());
        $grid->column('name', __('Tên đối tác'));
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('url', __('URL'));

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
    protected function detail($id): Show
    {
        $show = new Show(PartnerModel::findOrFail($id));
        $show->field('name', __('Tên đối tác'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('url', __('URL'));

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
        $form = new Form(new PartnerModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('partner');
        }
        $form->text('name', __('Tên đối tác'))->required();
        $form->text('url', __('URL'))->required();
        $form->image('image', __('Hình ảnh'));

        return $form;
    }
}
