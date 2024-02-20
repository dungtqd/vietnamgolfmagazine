<?php

namespace App\Admin\Controllers;

use App\Models\AdvertisementModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class
AAdvertisementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý quảng cáo';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new AdvertisementModel());
        $grid->column('branch', __('Tên nhãn hàng'))->filter('like');
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('layout.name', __('Layout'));
        $grid->column('from_date', __('Từ ngày'));
        $grid->column('to_date', __('Tới ngày'));
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core",'Status', "grid");
        });
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
        $show = new Show(AdvertisementModel::findOrFail($id));
        $show->field('branch', __('Tên nhãn hàng'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('layout.name', __('Layout'));
        $show->field('from_date', __('Từ ngày'));
        $show->field('to_date', __('Tới ngày'));
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
    protected function form(): Form
    {
        $layoutOptions = UtilsCommonHelper::getAllLayouts();
        $layoutDefault = $layoutOptions->keys()->first();

        $statusOptions = (new UtilsCommonHelper)->commonCode("Core", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();

        $form = new Form(new AdvertisementModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('advertisement');
        }

        $form->text('branch', __('Tên nhãn hàng'))->required();
        $form->image('image', __('Hình ảnh'));
        $form->select('layout_id', __('Layout'))->options($layoutOptions)->required()->default($layoutDefault);
        $form->date('from_date', __('Từ ngày'))->format('YYYY-MM-DD')->required();
        $form->date('to_date', __('Tới ngày'))->format('YYYY-MM-DD')->required();
        $form->number('status', __('Trạng thái'))->required();  //TODO: lấy từ config chuyển sang select

        return $form;
    }
}
