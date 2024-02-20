<?php

namespace App\Admin\Controllers;

use App\Models\ModuleDetailModel;
use App\Models\ModuleModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AModuleDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý chi tiết module (Vote)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new ModuleDetailModel());
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('banner.name', __('Banner'));
        $grid->column('title', __('Tiêu đề'))->filter('like');
        $grid->column('layout.name', __('Bố cục'));
        $grid->column('module.name', __('Module'));
        $grid->column('width', __('Kích thước chiều dài'));
        $grid->column('height', __('Kích thước chiều cao'));
        $grid->column('presentation', __('Cách trình bày'));  //TODO: Sau lấy từ config
        $grid->column('position', __('Vị trí')); //TODO: Sau lấy từ config
//        $grid->column('status', __('Trạng thái')); //TODO: Sau lấy từ config
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core",'Status', "grid");
        });
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
    protected function detail($id): Show
    {
        $show = new Show(ModuleDetailModel::findOrFail($id));
        $show->field('language.name', __('Ngôn ngữ'));
        $show->field('banner.name', __('Banner'));
        $show->field('title', __('Tiêu đề'));
        $show->field('layout.name', __('Bố cục'));
        $show->field('module.name', __('Module'));
        $show->field('width', __('Kích thước chiều dài'));
        $show->field('height', __('Kích thước chiều cao'));
        $show->field('presentation', __('Cách trình bày')); //TODO: Sau lấy từ config
        $show->field('position', __('Vị trí')); //TODO: Sau lấy từ config
        $show->field('status', __('Trạng thái'));  //TODO: Sau lấy từ config
        $show->field('order', __('Sắp xếp'));

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
        $bannerDefault = $bannerOptions->keys()->first();

        $moduleOptions = (new UtilsCommonHelper)->getAllModules();
        $moduleDefault = $moduleOptions->keys()->first();

        $layoutOptions = (new UtilsCommonHelper)->getAllLayouts();
        $layoutDefault = $layoutOptions->keys()->first();

        $form = new Form(new ModuleDetailModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('module_detail');
            $bannerId = $form->model()->find($id)->getOriginal("banner_id");
            $moduleId = $form->model()->find($id)->getOriginal("module_id");
            $layoutId = $form->model()->find($id)->getOriginal("layout_id");

            $form->select('banner_id', __('Banner'))->options($bannerOptions)->required()->default($bannerId);
            $form->select('module_id', __('Module'))->options($moduleOptions)->required()->default($moduleId);
            $form->select('layout_id', __('Bố cục'))->options($layoutOptions)->required()->default($layoutId);
        } else {
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
            $form->select('module_id', __('Module'))->options($moduleOptions)->required()->default($moduleDefault);
            $form->select('banner_id', __('Banner'))->options($bannerOptions);
            $form->select('layout_id', __('Bố cục'))->options($layoutOptions)->required()->default($layoutDefault);
        }

        $form->ckeditor('title', __('Tiêu đề'));
        $form->number('width', __('Kích thước chiều dài'));
        $form->number('height', __('Kích thước chiều cao'));
        $form->text('presentation', __('Cách trình bày'));  //TODO: Sau lấy từ config -> sửa thành select
        $form->text('position', __('Vị trí')); //TODO: Sau lấy từ config -> sửa thành select
        $form->number('status', __('Trạng thái')); //TODO: Sau lấy từ config
        $form->number('order', __('Sắp xếp'));

        return $form;
    }
}
