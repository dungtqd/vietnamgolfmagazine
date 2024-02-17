<?php

namespace App\Admin\Controllers;

use App\Models\ConfigSeoModel;
use App\Models\VoteBannerModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AConfigSeoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý cấu hình SEO';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ConfigSeoModel());
        $grid->column('seo_url', __('SEO url'));
        $grid->column('image', __('Hình ảnh'));
        $grid->column('meta_keyword', __('Meta keyword'))->textarea();
        $grid->column('meta_title', __('Meta title'))->textarea();
        $grid->column('meta_description', __('Meta description'))->textarea();
        $grid->column('google_analytics', __('Google Analytics'))->textarea();
        $grid->column('google_search_console', __('Google search console'))->textarea();
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
        $show = new Show(ConfigSeoModel::findOrFail($id));
        $show->field('seo_url', __('SEO url'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('meta_keyword', __('Meta keyword'));
        $show->field('meta_title', __('Meta title'));
        $show->field('meta_description', __('Meta description'));
        $show->field('google_analytics', __('Google Analytics'));
        $show->field('google_search_console', __('Google search console'));
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

        $form = new Form(new ConfigSeoModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('seo_config');
        } else {
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
        }
        $form->text('seo_url', __('SEO url'));
        $form->image('image', __('Hình ảnh'));
        $form->textarea('meta_keyword', __('Meta keyword'));
        $form->textarea('meta_title', __('Meta title'));
        $form->textarea('meta_description', __('Meta description'));
        $form->textarea('google_analytics', __('Google Analytics'));
        $form->textarea('google_search_console', __('Google search console'));

        return $form;
    }
}
