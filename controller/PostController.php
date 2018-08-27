<?php
/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/15
 * Time: 14:42
 */

class PostController
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {

    }

    /**
     * 記事一覧
     */
    public function indexAction()
    {

        header("Location: ./public/views/blog.php?page=1");
        exit;
    }

    /**
     *記事編集
     */
    public function editAction()
    {

        header("Location: ./public/views/blog_edit.php");
        exit;
    }


    /**
     *記事投稿
     */
    public function insertAction()
    {

        header("Location: ./public/views/post.php");
        exit;
    }

    /**
     *記事検索
     */
    public function searchAction()
    {

        header("Location: ./public/views/search.php");
        exit;
    }

}
