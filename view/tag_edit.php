<?php

session_start();

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

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
//    ログインしている
    $_SESSION['time'] = time();

    $users = $pdo->prepare('SELECT * FROM users WHERE id=?');
    $users->execute(array($_SESSION['id']));
    $user = $users->fetch();
}else{
//    ログインしていない
    header('Location: login.php');
    exit();
}



?>


<?php

require('../connect2.php');


if (!empty($_POST)) {
//    エラー項目の確認
    if ($_POST['tag_name'] == '') {
        $error['tag_name'] = 'blank';
    }


//    重複アカウントのチェック
    if (empty($error)) {
        $sql = $pdo->prepare('SELECT COUNT(*) AS cnt FROM tag WHERE tag_name=? and user_id=?');
        $sql->bindValue(1, $_POST['tag_name']);
        $sql->bindValue(2, $_SESSION['user_id']);
        $sql->execute();

        $record = $sql->fetch();
        if ($record['cnt'] > 0) {
            $error['tag_name'] = 'duplicate';
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

//    $_POST['category_name'] = $category['category_name'];



    if (empty($error)) {
        $_SESSION['join'] = $_POST;

        $stmt = $pdo->prepare('update tag set tag_name = :tag_name where id = :id');
        $stmt->bindParam(':tag_name', $_POST['tag_name'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
        $stmt->execute();
        unset($_SESSION['join']);

        header('Location: tag.php');
        exit();

    }



//else文で閉じないと、エラーが起きる。ifじゃなかった時には空の文字列を入れておく。
}else{
    $error = [];
    $error['tag_name'] = '';
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
    <a class="navbar-brand" href="">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto menu">
            <li class="nav-item">
                <a class="nav-link" href="blog.php?page=1">記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="post.php">投稿 <span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item menu__single">
                <a class="nav-link init-bottom" href="category.php">カテゴリー</a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="tag.php">タグ</a>
            </li>
            <li class="nav-item username">
                <a class="nav-lin　usename2">ようこそ、<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>さん</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <div class="nav-item logout">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="../logout.php">ログアウト</a></button>

        </div>
    </div>
</nav>

<div class="menyu_title">
    <h1>タグ編集</h1>
</div>



<form class="forms" action="" method="post">


    <div class="msr_text_02">
        <!--        <label>カテゴリー登録</label>-->
        <label>編集するタグ名：<?php echo $_POST['tag_name']; ?></label>
        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
        <input type="text" name="tag_name" />
        <?php if ($error['tag_name'] == 'blank'): ?>
            <p class="error">*新しいカテゴリーを入力してください</p>
        <?php endif; ?>
        <?php if ($error['tag_name'] == 'duplicate'): ?>
            <!--            <p class="error">*指定されたカテゴリーはすでに登録されています</p>-->
        <?php endif; ?>
    </div>



    <p class="msr_sendbtn_02">
        <input type="submit" value="登録する">
    </p>

</form>







</body>
</html>

