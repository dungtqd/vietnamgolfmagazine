<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ProgramModel;
use App\Util\Constant;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AProgramController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý hạng mục bình chọn';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProgramModel());
        $grid->column('language.name', __('Ngôn ngữ'))->filter('like');
        $grid->column('parent.name', __('Hạng mục cha'))->filter('like');
        $grid->column('code', __('Mã hạng mục'));
        $grid->column('name', __('Tên hạng mục bình chọn'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('avatar_image', __('Ảnh đại diện'))->image();
//        $grid->column('cover_image', __('Ảnh bìa'))->image();
        $grid->column('seo_title', __('Tiêu đề SEO'));
        $grid->column('meta_keyword', __('Meta keyword'));
        $grid->column('seo_url', __('SEO URL'));
        $grid->column('meta_description', __('Meta description'));
        $grid->column('robots_tag', __('Robots tag'));
        $grid->column('order', __('Sắp xếp'));
        $grid->column('start_date', __('Ngày bắt đầu'));
        $grid->column('end_date', __('Ngày kết thúc'));
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('language_id', 'asc');
        $grid->model()->orderBy('created_at', 'desc');
        $grid->fixColumns(0, -1);
//        $grid->disableFilter();
        $grid-> filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $languageOptions = UtilsCommonHelper::getAllLanguages();
            $programOptions = UtilsCommonHelper::getAllRootPrograms();
            $programOptions->prepend('Không có', '0');
            $filter->equal('language_id', 'Ngôn ngữ')->select($languageOptions);
            $filter->equal('parent_id', 'Hạng mục cha')->select($programOptions);
            $filter->like('name', 'Tên hạng mục bình chọn');
            $filter->date('created_at', 'Ngày tạo');
            $filter->date('updated_at', 'Ngày cập nhật');
        });
//        $grid->expandFilter();
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
        $show = new Show(ProgramModel::findOrFail($id));
        $show->field('language.name', __('Ngôn ngữ'));
        $show->field('code', __('Mã hạng mục'));
        $show->field('name', __('Tên hạng mục bình chọn'));
        $show->field('description', __('Mô tả'));
        $show->field('avatar_image', __('Ảnh đại diện'))->image();
//        $show->field('cover_image', __('Ảnh bìa'))->image();
        $show->field('parent.name', __('Hạng mục cha'));
        $show->field('seo_title', __('Tiêu đề SEO'));
        $show->field('meta_keyword', __('Meta keyword'));
        $show->field('seo_url', __('SEO URL'));
        $show->field('meta_description', __('Meta description'));
        $show->field('robots_tag', __('Robots tag'));
        $show->field('order', __('Sắp xếp'));
        $show->field('start_date', __('Ngày bắt đầu'));
        $show->field('end_date', __('Ngày kết thúc'));
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
        $programOptions = UtilsCommonHelper::getAllRootPrograms();
        $programOptions->prepend('Không có', '0');
        $programDefault = $programOptions->keys()->first();

        //ngôn ngữ default lúc tạo là tiếng Việt
        $originalProgramOptions = UtilsCommonHelper::getOriginalProgram();
        $originalProgramOptions->prepend('Không có', '0');
        $originalProgramDefault = $originalProgramOptions->keys()->first();

        $languageOptions = UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();

        $form = new Form(new ProgramModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('program');
            $parentId = $form->model()->find($id)->getOriginal("parent_id");
            $languageId = $form->model()->find($id)->getOriginal("language_id");
//            dd()
            $originalProgramId = $form->model()->find($id)->getOriginal("original_program");

//            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->default($languageId)->disable()->value($languageId);
            $form->select('parent_id', __('Hạng mục cha'))->options($programOptions)->default($parentId);
            $form->select('original_program', __('Hạng mục gốc theo tiếng Việt'))->options($originalProgramOptions)->default($originalProgramId)->disable()->value($originalProgramId);
            $form->text('code', __('Mã hạng mục'))->disable()->required();
        } else {
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
            $form->select('parent_id', __('Hạng mục cha'))->options($programOptions)->required()->default($programDefault);
            $form->select('original_program', __('Hạng mục gốc theo tiếng Việt'))->options($originalProgramOptions)->required()->default($originalProgramDefault);
            $form->hidden('code', __('Mã hạng mục'));

            $form->saving(function ($form) {
                $originalProgramId = $form->original_program;
                $intOriginalProgramId= (int)$originalProgramId;

                $languageId = $form->language_id;
                $intLanguageId= (int)$languageId;
                $languageDefault = UtilsCommonHelper::getOriginalLanguage();
                //check neu ngon ngu goc + original_program =0 thi tao ma code
                if ($intLanguageId === $languageDefault && $intOriginalProgramId === Constant::PARENT_ID_ROOT) {
                    //tao moi ma code
                    error_log("tao moi ma code");
                    $form->code = UtilsCommonHelper::generateCode();
                }else{
                    //tim ma code cu
                    $existCode=UtilsCommonHelper::getExistProgramCode($originalProgramId, $languageDefault);
                    error_log("lay lai code cu");
                    error_log($existCode);
                    $form->code =$existCode;
                }
            });
        }
        $form->text('name', __('Tên hạng mục bình chọn'))->required();
        $form->textarea('description', __('Mô tả'));
        $form->image('avatar_image', __('Ảnh đại diện'));


        $form->text('seo_title', __('Tiêu đề SEO'));
        $form->text('meta_keyword', __('Meta keyword'));
        $form->text('seo_url', __('SEO URL'));
        $form->textarea('meta_description', __('Meta description'));
        $form->text('robots_tag', __('Robots tag'));
        $form->number('order', __('Sắp xếp'))->required();
        $form->datetime('start_date', __('Ngày bắt đầu'))->required();
        $form->datetime('end_date', __('Ngày kết thúc'))->required();



        return $form;
    }
}
