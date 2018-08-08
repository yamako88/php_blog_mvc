<?php
ini_set('display_errors', "On");

session_start();
require('../connect2.php');

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
    $_SESSION['user_id']=$user['id'];

} else {
//    ログインしていない
    header('Location: ../login.php');
    exit();
}
?>

<?php

require('../connect2.php');

if (!empty($_POST)) {
//    エラー項目の確認
    if ($_POST['category_name'] == '') {
        $error['category_name'] = 'blank';
    }


//    重複アカウントのチェック
    if (empty($error)) {
        $sql = $pdo->prepare('SELECT COUNT(*) AS cnt FROM category WHERE category_name=? and user_id_category=?');
        $sql->bindValue(1, $_POST['category_name']);
        $sql->bindValue(2, $_SESSION['user_id']);
        $sql->execute();

        $record = $sql->fetch();
        if ($record['cnt'] > 0) {
            $error['category_name'] = 'duplicate';
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

        $stmt = $pdo->prepare('insert into category (category_name, user_id_category) values(:category_name, :user_id_category)');
        $stmt->bindParam(':category_name', $_POST['category_name'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id_category', $user['id'], PDO::PARAM_STR);
        $stmt->execute();
        unset($_SESSION['join']);

        header('Location: category.php');
        exit();



    }



//else文で閉じないと、エラーが起きる。ifじゃなかった時には空の文字列を入れておく。
}else{
    $error = [];
$error['category_name'] = '';
}

?>

<?php
ini_set('display_errors', "On");

try {

    /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */

    // データベースに接続
    $dbh = new PDO(
        'mysql:dbname=php_blog;host=localhost;charset=utf8mb4',
        'root',
        'root',
        [
//            エラーの設定
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    $statement = $dbh->prepare("SELECT * FROM category where user_id_category = ?");
    //bindValueメソッドでパラメータをセット
    $statement->bindValue(1, $_SESSION['user_id']);


    //executeでクエリを実行
    $statement->execute();
    $rows = $statement->fetchAll();
    $dbh = null;

} catch (PDOException $e) {

    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());

}

header('Content-Type: text/html; charset=utf-8');




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
                <!--                <a class="nav-link" href="#">記事 <span class="sr-only">(current)</span></a>-->
                <a class="nav-link" href="blog.php?page=1">記事</a>
            </li>
            <li class="nav-item">
                <!--                <a class="nav-link" href="#">投稿</a>-->
                <a class="nav-link" href="post.php">投稿 <span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item active menu__single">
                <a class="nav-link init-bottom" href="#">カテゴリー</a>
<!--                <ul class="menu__second-level">-->
<!--                    <li><a href="category.php">カテゴリー登録</a></li>-->
<!--                    <li><a href="category_list.php">カテゴリー一覧</a></li>-->
<!--                </ul>-->
            </li>

            <li class="nav-item">
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
    <h1>カテゴリー</h1>
</div>



<form class="forms" action="" method="post">


    <div class="msr_text_02">
        <label>カテゴリー登録</label>
        <input type="text" name="category_name" />
        <?php if ($error['category_name'] == 'blank'): ?>
            <p class="error">*カテゴリーを入力してください</p>
        <?php endif; ?>
        <?php if ($error['category_name'] == 'duplicate'): ?>
            <p class="error">*指定されたカテゴリーはすでに登録されています</p>
        <?php endif; ?>
    </div>



    <p class="msr_sendbtn_02">
        <input type="submit" value="登録する">
    </p>

</form>



<?php
foreach($rows as $row) {
    ?>

    <div class="card">

        <div class="card-body">
            <h3 class="card-title"><?php echo $row['category_name']; ?></h3>

            <form action="category_edit.php" method="post">
                <input type="hidden" name="category_name" value="<?php echo $row['category_name']; ?>">
                <input type="hidden" name="id" value="<?php echo $row['category_id']; ?>">
                <input type="submit" value="編集" class="btn btn-primary">
            </form>

            <div class="deleat"  onclick="return confirm('削除します。\nよろしいですか？');">
            <form action="category_delete.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['category_id']; ?>">
                <input type="submit" value="削除" class="btn btn-primary">
            </form>
            </div>

        </div>


    </div>
    <?php
}

?>






</body>
</html>
