<?php

ini_set('display_errors', "On");
require_once('../../model/UserModel.php');
require_once('../../service/validation/LoginValidation.php');

session_start();

$errors = [];

if (!empty($_POST)) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);

        $error['login'] = '';

        // バリデーションチェック
        $loginValidation = new LoginValidation();
        $errors = $loginValidation->addValidation($email, $password);


        // バリデーションエラーがない場合
        if (count($errors) === 0) {
            $userModel = new UserModel();
            $user = $userModel->login($email, $password);
            if (count($user) > 0) {

                $_SESSION['id'] = $user[0];
                $_SESSION['time'] = time();

                header("Location: /blog");
                exit();
            } else {
                $errors['login'] = '';
                $error['login'] = 'failed';
            }
        }
    }
} else {
    $errors['login'] = '';
    $error['login'] = '';

}


//安全安心なトークンを作成(32桁数)
$TOKEN_LENGTH = 16;
$tokenByte = openssl_random_pseudo_bytes($TOKEN_LENGTH);
$token = bin2hex($tokenByte);

//セッションに設定
$_SESSION['token'] = $token;


?>


<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UFT-8”>
    <title>phpで作ったblog</title>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<!--ヘッダー-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

        </ul>
        <div class="nav-item logout">
            <a href="/new_account">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">新規登録</button>
            </a>
        </div>


    </div>
</nav>


<div class="menyu_title">
    <h1>ログイン</h1>
</div>


<form class="forms" action="" method="POST">
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <div class="msr_text_02">
        <label>メールアドレス</label>
        <input type="text" name="email"/>

        <?php if ($errors['login'] == 'blank'): ?>
            <p class="error">*メールアドレスとパスワードをご記入ください</p>
        <?php endif; ?>

        <?php if ($error['login'] == 'failed'): ?>
            <p class="error">*ログインに失敗しました。正しくご記入ください。</p>
        <?php endif; ?>
    </div>


    <div class="msr_text_02">
        <label>パスワード</label>
        <input type="password" name="password"/>
    </div>


    <p class="msr_sendbtn_02">

        <input type="submit" name="login" value="ログイン">
    </p>
</form>


</body>
</html>
