<?php

namespace App\Admin\Controllers;

use App\Models\ModuleModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AModuleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý module (Vote)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ModuleModel());
        $grid->column('name', __('Tên module'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('language.name', __('Ngôn ngữ'));

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
        $show = new Show(ModuleModel::findOrFail($id));
        $show->field('name', __('Tên module'));
        $show->field('description', __('Mô tả'));
        $show->field('language.name', __('Ngôn ngữ'));

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
        $languageOptions = (new UtilsCommonHelper)->getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();

        $form = new Form(new ModuleModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('module');
        }else{
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
        }
        $form->text('name', __('Tên module'))->required();
        $form->textarea('description', __('Mô tả'));

        return $form;
    }
}
