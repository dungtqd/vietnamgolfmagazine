<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ContactModel;
use App\Models\OrderFormModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AOrderFormController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý đăng ký đặc san';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderFormModel());
        $grid->column('name', __('Họ tên'))->filter('like');
        $grid->column('phone_number', __('Số điện thoại'))->filter('like');
        $grid->column('email', __('Email'))->filter('like');
        $grid->column('address', __('Địa chỉ'));
        $grid->column('from_month', __('Từ tháng'));
        $grid->column('to_month', __('Tới tháng'));
        $grid->column('note', __('Ghi chú'));
//        $grid->column('order_status', __('Trạng thái đặt hàng'));  //TODO: lấy từ config
        $grid->column('order_status', __('Trạng thái đặt hàng'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Read", "Order_Status","grid");
        });
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
    protected function detail($id)
    {
        $show = new Show(OrderFormModel::findOrFail($id));
        $show->field('name', __('Họ tên'));
        $show->field('phone_number', __('Số điện thoại'));
        $show->field('email', __('Email'));
        $show->field('address', __('Địa chỉ'));
        $show->field('from_month', __('Từ tháng'));
        $show->field('to_month', __('Tới tháng'));
        $show->field('note', __('Ghi chú'));
        $show->field('order_status', __('Trạng thái đặt hàng'));  //TODO: lấy từ config

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

        $form = new Form(new OrderFormModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('order_form');
        }
        $form->text('name', __('Họ tên'))->required();
        $form->mobile('phone_number', __('Số điện thoại'))->required();
        $form->email('email', __('Email'));
        $form->text('address', __('Địa chỉ'))->required();
        $form->date('from_month', __('Từ tháng'))->format('YYYY-MM-DD')->required();
        $form->date('to_month', __('Tới tháng'))->format('YYYY-MM-DD')->required();
        $form->text('note', __('Ghi chú'));
        $form->number('order_status', __('Trạng thái đặt hàng'))->required();  //TODO: lấy từ config chuyển sang select

        return $form;
    }
}
