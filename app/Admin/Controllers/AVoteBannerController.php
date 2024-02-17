<?php

namespace App\Admin\Controllers;

use App\Models\LanguageModel;
use App\Models\ProvinceModel;
use App\Models\VoteBannerModel;
use App\Models\ZoneModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AVoteBannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý banner (Vote)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VoteBannerModel());
        $grid->column('name', __('Tên banner'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('code', __('Mã code'));
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
        $show = new Show(VoteBannerModel::findOrFail($id));
        $show->field('name', __('Tên banner'));
        $show->field('description', __('Mô tả'));
        $show->field('code', __('Mã code'));
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

        $form = new Form(new VoteBannerModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('vote_banner');
        }
        $form->text('name', __('Tên '))->required();
        $form->textarea('description', __('Mô tả'));
        $form->text('code', __('Mã code'));
        $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);

        return $form;
    }
}
