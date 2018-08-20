<?php
/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/20
 * Time: 16:06
 */

class TagController
{
    /**
     * TagController constructor.
     */
    public function __construct()
    {

    }

    /**
     * タグ登録
     */
    public function addAction()
    {

        header("Location: ./public/views/tag.php");
        exit;
    }

    /**
     * タグ編集
     */
    public function editAction()
    {

        header("Location: ./public/views/tag_edit.php");
        exit;
    }

}