<?php

namespace App\Admin\Controllers;

use App\Models\VoteBannerDetailModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AVoteBannerDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý chi tiết banner (Vote)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VoteBannerDetailModel());
        $grid->column('banner.name', __('Banner'))->filter('like');
        $grid->column('title', __('Tiêu đề'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('link', __('Đường dẫn'));
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('desktop_image', __('Ảnh desktop'))->image();
        $grid->column('mobile_image', __('Ảnh mobile'))->image();
        $grid->column('order', __('Sắp xếp'));

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
        $show = new Show(VoteBannerDetailModel::findOrFail($id));
        $show->field('title', __('Tiêu đề'));
        $show->field('description', __('Mô tả'));
        $show->field('link', __('Đường dẫn'));
        $show->field('language.name', __('Ngôn ngữ'));
        $show->field('desktop_image', __('Ảnh desktop'))->image();
        $show->field('mobile_image', __('Ảnh mobile'))->image();
        $show->field('order', __('Sắp xếp'));
        $show->field('banner.name', __('Banner'));

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

        $bannerOptions = (new UtilsCommonHelper)->getAllVoteBanners();
        $bannerOptions->prepend('All pages','0');
        $bannerDefault = $bannerOptions->keys()->first();

        $form = new Form(new VoteBannerDetailModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('vote_banner_detail');
            $bannerId = $form->model()->find($id)->getOriginal("banner_id");

            $form->select('banner_id', __('Banner'))->options($bannerOptions)->required()->default($bannerId);
        } else {
            $form->select('banner_id', __('Banner'))->options($bannerOptions)->required()->default($bannerDefault);
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
        }
        $form->ckeditor('title', __('Tiêu đề'))->required();
        $form->ckeditor('description', __('Mô tả'));
        $form->text('link', __('Đường dẫn'));
        $form->image('desktop_image', __('Ảnh desktop'));
        $form->image('mobile_image', __('Ảnh mobile'));
        $form->number('order', __('Sắp xếp'))->required();

        return $form;
    }
}
