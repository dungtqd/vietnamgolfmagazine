<?php

namespace App\Admin\Controllers;

use App\Models\LanguageModel;
use App\Models\ProvinceModel;
use App\Models\ReadBannerModel;
use App\Models\VoteBannerModel;
use App\Models\ZoneModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class
AReadBannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý banner (Read)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new ReadBannerModel());
        $grid->column('name', __('Tên banner'))->filter('like');
        $grid->column('code', __('Mã code'));
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('layout.name', __('Layout'));
        $grid->column('type', __('Hiển thị'));  //TODO: sau lay tu config các cách hiển thị: slideshow, default,...
        $grid->column('note', __('Ghi chú'));
        $grid->column('status', __('Trạng thái'));  //TODO: lấy từ config


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
        $show = new Show(ReadBannerModel::findOrFail($id));
        $show->field('name', __('Tên banner'));
        $show->field('code', __('Mã code'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('layout.name', __('Layout'));
        $show->field('type', __('Hiển thị'));  //TODO: sau lay tu config các cách hiển thị: slideshow, default,...
        $show->field('note', __('Ghi chú'));
        $show->field('status', __('Trạng thái'));  //TODO: lấy từ config

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
        $layoutOptions = UtilsCommonHelper::getAllLayouts();
        $layoutDefault = $layoutOptions->keys()->first();

        $form = new Form(new ReadBannerModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('read_banner');
        }

        $form->text('name', __('Tên banner'))->required();
        $form->text('code', __('Mã code'))->required();
        $form->image('image', __('Hình ảnh'));
        $form->select('layout_id', __('Layout'))->options($layoutOptions)->required()->default($layoutDefault);
        $form->number('type', __('Hiển thị'));  //TODO: sau lay tu config các cách hiển thị: slideshow, default,...
        $form->text('note', __('Ghi chú'));
        $form->number('status', __('Trạng thái'));  //TODO: lấy từ config chuyển sang select

        return $form;
    }
}
