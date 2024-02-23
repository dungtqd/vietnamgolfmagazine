<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ProgramProductModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class AProgramProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý hạng mục - ứng viên';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProgramProductModel());
        $grid->column('program.name', __('Tên hạng mục'))->filter('like');
        $grid->column('product.name', __('Tên ứng viên'))->filter('like');
//        $grid->column('programCode.name', __('Tên hạng mục'))->filter('like');
//        $grid->column('productCode.name', __('Tên ứng viên'))->filter('like');
        $grid->column('order', __('Sắp xếp'));
//        $grid->column('status', __('Trạng thái'));  //todo: thêm convert status
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", 'Status', "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('created_at', 'desc');
        $grid->fixColumns(0, -1);
//        $grid->disableFilter();
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $languageOptions = UtilsCommonHelper::getAllLanguages();
            $productOptions = UtilsCommonHelper::getOriginalProductCode();
            $programOptions = UtilsCommonHelper::getOriginalProgramCode();
//            $filter->equal('language_id', 'Ngôn ngữ')->select($languageOptions);
            $filter->equal('program_code', 'Tên hạng mục')->select($programOptions);
            $filter->equal('product_code', 'Tên ứng viên')->select($productOptions);
            $filter->date('created_at', 'Ngày tạo');
            $filter->date('updated_at', 'Ngày cập nhật');
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
        $show = new Show(ProgramProductModel::findOrFail($id));
        $show->field('program.name', __('Tên hạng mục'));
        $show->field('product.name', __('Tên ứng viên'));
        $show->field('order', __('Sắp xếp'));
        $show->field('status', __('Trạng thái'))->as(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", 'Status', null);
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
//        $programOptions = UtilsCommonHelper::getAllPrograms();
        $programOptions = UtilsCommonHelper::getOriginalProgramCode();
        $programDefault = $programOptions->keys()->first();

//        $productOptions = UtilsCommonHelper::getAllProducts();
        $productOptions = UtilsCommonHelper::getOriginalProductCode();
        $productDefault = $productOptions->keys()->first();

        $statusOptions = UtilsCommonHelper::commonCode("Core", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();
        $programProductId = null;
        $form = new Form(new ProgramProductModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('program_product');
            $programProductId = $id;
            $programId = $form->model()->find($id)->getOriginal("program_id");
            $productId = $form->model()->find($id)->getOriginal("product_id");
            $programCode = $form->model()->find($id)->getOriginal("program_code");
            $productCode = $form->model()->find($id)->getOriginal("product_code");


            $form->select('program_code', __('Tên hạng mục'))->options($programOptions)->default($programCode);
            $form->select('product_code', __('Tên ứng viên'))->options($productOptions)->default($productCode);
//            $form->hidden('program_id', __('Tên hạng mục'))->value($programId);
//            $form->hidden('product_id', __('Tên ứng viên')) - value($productId);
        } else {
            $form->select('program_code', __('Tên hạng mục'))->options($programOptions)->required()->default($programDefault);
            $form->select('product_code', __('Tên ứng viên'))->options($productOptions)->required()->default($productDefault);
            $form->hidden('program_id', __('Tên hạng mục'));
            $form->hidden('product_id', __('Tên ứng viên'));
            $form->saving(function ($form) {
                $programCode = $form->program_code;
                $programId = UtilsCommonHelper::getOriginalProgramByCode($programCode);
                $form->program_id = $programId;

                $productCode = $form->product_code;
                $productId = UtilsCommonHelper::getOriginalProductByCode($productCode);
                $form->product_id = $productId;

            });
        }

        $form->number('order', __('Sắp xếp'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();

        $form->saving(function ($form) use ($programProductId) {
            $programCode = $form->program_code;
            $productCode = $form->product_code;

            $existProgramProduct = UtilsCommonHelper::getExistProgramProduct($programCode, $productCode);
            error_log($programProductId);
            error_log($existProgramProduct);
            if ($existProgramProduct !== 0 && $programProductId === null) {
                $error = new MessageBag([
                    'title' => 'Tạo dữ liệu lỗi',
                    'message' => 'Hạng mục và ứng viên  đã tồn tại',
                ]);

                return back()->with(compact('error'));
            }

        });

        return $form;
    }
}
