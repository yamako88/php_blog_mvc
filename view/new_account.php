
<?php

require('../connect2.php');

session_start();

if (!empty($_POST)) {
//    エラー項目の確認
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }


//    重複アカウントのチェック
    if (empty($error)) {
        $sql = sprintf('SELECT COUNT(*) AS cnt FROM users WHERE email="%s"',
        mysqli_real_escape_string($db, $_POST['email'])
        );

        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $table = mysqli_fetch_assoc($record);

        if ($table['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }


    }




    // データベースに接続
    $pdo = new PDO(
        'mysql:dbname=php_blog;host=localhost;charset=utf8mb4',
        'root',
        'root',
        [
//            エラーの設定
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );


    if (empty($error)) {
        $_SESSION['join'] = $_POST;

        $stmt = $pdo->prepare('insert into users (name,email,password) values(:name,:email,:password)');
        $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
        $stmt->execute();
        unset($_SESSION['join']);

        header('Location: thanks.php');
        exit();



    }




}
?>














<!DOCTYPE html>
<html lang = “ja”>
<head>
    <meta charset = “UFT-8”>
    <title>phpで作ったblog</title>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<!--ヘッダー-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

        </ul>

        <div class="nav-item logout">
            <a  href="/login.php">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">ログイン</button>
            </a>
        </div>

    </div>
</nav>










<div class="menyu_title">
    <h1>新規登録</h1>
</div>





<form class="forms" action="" method="post">
    <div class="msr_text_02">
        <label>ニックネーム</label>
        <input type="text" name="name"
            value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'utf-8'); ?>" />
        <?php if ($error['name'] == 'blank'): ?>
            <p class="error">*ニックネームを入力してください</p>
        <?php endif; ?>
    </div>
    <div class="msr_text_02">
        <label>メールアドレス</label>
        <input type="text" name="email"
               value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'utf-8'); ?>" />
        <?php if ($error['email'] == 'blank'): ?>
            <p class="error">*メールアドレスを入力してください</p>
        <?php endif; ?>

        <?php if ($error['email'] == 'duplicate'): ?>
        <p class="error">*指定されたメールアドレスはすでに登録されています</p>
        <?php endif; ?>

    </div>
    <div class="msr_text_02">
        <label>パスワード</label>
        <input type="password" name="password" maxlength="20"
               value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'utf-8'); ?>" />
        <?php if ($error['password'] == 'blank'): ?>
            <p class="error">*パスワードを入力してください</p>
        <?php endif; ?>
        <?php if ($error['password'] == 'length'): ?>
            <p class="error">*パスワードは4文字以上で入力してください</p>
        <?php endif; ?>
    </div>





    <p class="msr_sendbtn_02">
        <input type="submit" value="登録する">
    </p>

</form>



</body>
</html>
