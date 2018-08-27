<?php
/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/18
 * Time: 15:22
 */

class CategoryController
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {

    }

    /**
     * カテゴリー登録
     */
    public function insertAction()
    {

        header("Location: ./public/views/category.php");
        exit;
    }

    /**
     * カテゴリー編集
     */
    public function editAction()
    {

        header("Location: ./public/views/category_edit.php");
        exit;
    }

}