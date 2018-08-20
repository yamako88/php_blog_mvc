<?php

/**
 * Class UserController
 */
class UserController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {

    }

    /**
     * ユーザ登録
     */
    public function addAction()
    {

        header("Location: ./public/views/new_account.php");
        exit;
    }

    /**
     *ユーザー登録完了
     */
    public function thanksAction()
    {

        header("Location: ./public/views/thanks.php");
        exit;
    }

    /**
     * ログイン
     */
    public function loginAction()
    {

        header("Location: ./public/views/login.php");
        exit;
    }

    /**
     * ログアウト
     */
    public function logoutAction()
    {

        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Location: ./public/views/login.php");
        exit;
    }

}
