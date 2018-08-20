<?php
ini_set('display_errors', "On");
require_once('./route/RequestUrl.php');
require_once('./controller/PostController.php');
require_once('./controller/CategoryController.php');
require_once('./controller/UserController.php');
require_once('./controller/TagController.php');

$request = new RequestUrl();
$url = $request->getPathInfo();

// routing
switch ($url) {

    case '/':
    case '/blog':
        $postController = new PostController();
        $postController->indexAction();
        break;
    case '/blog_edit':
        $postController = new PostController();
        $postController->editAction();
        break;
    case '/post':
        $postController = new PostController();
        $postController->addAction();
        break;
    case '/search':
        $postController = new PostController();
        $postController->searchAction();
        break;
    case '/new_account':
        $userController = new UserController();
        $userController->addAction();
        break;
    case '/thanks':
        $userController = new UserController();
        $userController->thanksAction();
        break;
    case '/login':
        $userController = new UserController();
        $userController->loginAction();
        break;
    case '/logout':
        $userController = new UserController();
        $userController->logoutAction();
        break;
    case '/category':
        $categoryController = new CategoryController();
        $categoryController->addAction();
        break;
    case '/category_edit':
        $categoryController = new CategoryController();
        $categoryController->editAction();
        break;
    case '/tag':
        $tagController = new TagController();
        $tagController->addAction();
        break;
    case '/tag_edit':
        $tagController = new TagController();
        $tagController->editAction();
        break;
}

