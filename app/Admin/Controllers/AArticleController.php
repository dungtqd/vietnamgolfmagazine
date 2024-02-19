<?php

namespace App\Admin\Controllers;

use App\Models\ArticleModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AArticleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý bài viết';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ArticleModel());
        $grid->column('language.name', __('Ngôn ngữ'));
        $grid->column('category.name', __('Thể loại'));
        $grid->column('title', __('Tiêu đề'))->filter('like');
        $grid->column('description', __('Mô tả'));
        $grid->column('content', __('Nội dung'));
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('slug', __('Đường dẫn'));
        $grid->column('keywords', __('Từ khoá'));
        $grid->column('like_count', __('Số lượng thích'));


        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('updated_at', 'desc');
//        $grid->fixColumns(0, -1);
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
        $show = new Show(ArticleModel::findOrFail($id));
        $show->field('language.name', __('Ngôn ngữ'));
        $show->field('category.name', __('Thể loại'));
        $show->field('title', __('Tiêu đề'));
        $show->field('description', __('Mô tả'));
        $show->ckeditor('content', __('Nội dung'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('slug', __('Đường dẫn'));
        $show->field('keywords', __('Từ khoá'));
        $show->field('like_count', __('Số lượng thích'));

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
        $categoryOptions = UtilsCommonHelper::getAllChildrenCategories();
        $categoryDefault = $categoryOptions->keys()->first();

        $languageOptions = UtilsCommonHelper::getAllLanguages();
        $languageDefault = $languageOptions->keys()->first();

        $form = new Form(new ArticleModel());
        $form->hidden('slug', __('Đường dẫn'));
        $form->hidden('like_count', __('Số lượng thích'))->value(0);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('article');
            $parentId = $form->model()->find($id)->getOriginal("category_id");

            $form->select('category_id', __('Thể loại'))->options($categoryOptions)->default($parentId);
        } else {
            $form->select('language_id', __('Ngôn ngữ'))->options($languageOptions)->required()->default($languageDefault);
            $form->select('category_id', __('Thể loại'))->options($categoryOptions)->required()->default($categoryDefault);
        }
        $form->text('title', __('Tiêu đề'))->required();
        $form->textarea('description', __('Mô tả'));
        $form->ckeditor('content', __('Nội dung'))->required();
        $form->image('image', __('Hình ảnh'));
        $form->text('keywords', __('Từ khoá'));
        $form->saving(function ($form) {
            if (!($form->model()->id && $form->model()->title === $form->title)) {
                $form->slug = UtilsCommonHelper::create_slug($form->title, ArticleModel::get());
            }
        });

        return $form;
    }
}
