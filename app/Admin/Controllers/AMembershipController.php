<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ContactModel;
use App\Util\Constant;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AMembershipController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý thành viên';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ContactModel());
        $grid->column('phone_number', __('Số điện thooại'))->filter('like');
        $grid->column('subscribe_status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Read", 'Contact_Status', "grid");
        });

        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('updated_at', 'desc');
        $grid->fixColumns(0, -1);
        $grid->model()->where('type', '=', Constant::CONTACT_TYPE__MEMBERSHIP);
        $grid->disableFilter();

        $grid->actions(function ($actions) {
//            $blockDelete = $actions->row->block_delete;
//            if ($blockDelete === 1) {
                $actions->disableDelete();
//                $actions->disableEdit();
//            }
        });
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
        $show = new Show(ContactModel::findOrFail($id));
        $show->field('email', __('Email'));
        $show->field('subscribe_status', __('Trạng thái'))->as(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Read", 'Contact_Status',null);
        });

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

        $form = new Form(new ContactModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('contact');
            $form->text('email', __('Email'))->disable();
        } else {
            $form->text('email', __('Email'))->required();
        }
        $form->number('subscribe_status', __('Trạng thái'))->required();  //TODO: sau chuyen sang select

        return $form;
    }
}
