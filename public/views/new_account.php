<?php

// エラーを出力する
ini_set('display_errors', "On");
require_once('../../model/UserModel.php');
require_once('../../service/validation/UsersValidation.php');

$errors = [];

if (!empty($_POST)) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // バリデーションチェック
    $usersValidation = new UsersValidation();
    $errors = $usersValidation->addValidation($name, $email, $password);

    $errorss = array_filter($errors, 'strlen');

    // バリデーションエラーがない場合
    if (empty($errorss)) {
        $userModel = new UserModel();
        $userModel->insert_users($name, $email, $password);
        header("Location: /thanks");
        exit();
    }

} else {
    $errors = [];
    $errors['name'] = '';
    $errors['email'] = '';
    $errors['password'] = '';
}

?>


<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UFT-8”>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
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
            <a href="/login">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">ログイン</button>
            </a>
        </div>

    </div>
</nav>


<div class="menyu_title">
    <h1>新規登録</h1>
</div>


<form class="forms" action="" method="post">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <div class="msr_text_02">
        <label>ニックネーム</label>
        <input type="text" name="name"/>
        <?php if ($errors['name'] == 'blank'): ?>
            <p class="error">*ニックネームを入力してください</p>
        <?php endif; ?>
    </div>
    <div class="msr_text_02">
        <label>メールアドレス</label>
        <input type="email" name="email"/>
        <?php if ($errors['email'] == 'blank'): ?>
            <p class="error">*メールアドレスを入力してください</p>
        <?php endif; ?>

        <?php if ($errors['email'] == 'validate'): ?>
            <p class="error">*正しいメールアドレスを入力してください</p>
        <?php endif; ?>

        <?php if ($errors['email'] == 'duplicate'): ?>
            <p class="error">*指定されたメールアドレスはすでに登録されています</p>
        <?php endif; ?>

    </div>
    <div class="msr_text_02">
        <label>パスワード</label>
        <input type="password" name="password" maxlength="20"/>
        <?php if ($errors['password'] == 'blank'): ?>
            <p class="error">*パスワードを入力してください</p>
        <?php endif; ?>
        <?php if ($errors['password'] == 'length'): ?>
            <p class="error">*パスワードは4文字以上で入力してください</p>
        <?php endif; ?>
    </div>


    <p class="msr_sendbtn_02">
        <input type="submit" name="submit" value="登録する">
    </p>

</form>


</body>
</html>
