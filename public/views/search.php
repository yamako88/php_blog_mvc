<?php
ini_set('display_errors', "On");

session_start();
require_once('../../model/SessionModel.php');
require_once('../../model/SearchModel.php');
require_once('../../model/BlogModel.php');


//セッション状態確認
$session = $_SESSION['id'];
$time = $_SESSION['time'];

$sessionModel = new SessionModel();
$user = $sessionModel->session($session, $time);

if (empty($user)) {
//    ログインしていない
    header('Location: /login');
    exit();
}


if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search'], ENT_QUOTES, 'utf-8');
    $search_value = $search;
} else {

    header('Location: /blog');
    exit();
}

$searchModel = new SearchModel();
$rows = $searchModel->search($session, $search);

//投稿を削除する
if (!empty($_POST['delete'])) {

//    トークンチェック
    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);

        $delete = $_POST['delete'];

        $blogModel = new BlogModel();
        $rows = $blogModel->delete($delete);

        header('Location: /blog');
        exit();
    }
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
    <a class="navbar-brand" href="">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/blog">記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/post">投稿</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/category">カテゴリー</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/tag">タグ</a>
            </li>
            <li class="nav-item username">
                <a class="nav-lin　usename2">ようこそ、<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>さん</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="search.php" method="get">
            <input class="form-control mr-sm-2" type="text" name="search" value="<?php echo $search_value ?>"
                   placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索</button>
        </form>

        <div class="nav-item logout">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="/logout">ログアウト</a></button>

        </div>
    </div>
</nav>

<div class="menyu_title">
    <h1>記事</h1>
</div>


<?php

foreach ($rows as $row) {

    $rowid = $row['id'];

//    タグ
    $blogModel = new BlogModel();
    $rowss = $blogModel->tag($session, $rowid);

    ?>


    <div class="card">


        <div class="card-body">
            <h3 class="card-title"><?php echo $row['title']; ?></h3>
            <p class="card-text">
                <?php echo $row['text']; ?>
            </p>
            <p>投稿日時：<?php echo $row['date']; ?></p>
            <p>カテゴリー：<?php echo $row['category_name']; ?></p>

            <p>タグ：<?php foreach ($rowss as $rows) {
                    echo $rows['tag_name'];
                    echo "　";
                } ?></p>

            <form action="blog_edit.php" method="post">
                <input type="hidden" name="edit" value="sample">
                <input type="hidden" name="title" value="<?php echo $row['title']; ?>">
                <input type="hidden" name="text" value="<?php echo $row['text']; ?>">
                <input type="hidden" name="category_name" value="<?php echo $row['category_name']; ?>">
                <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                <input type="hidden" name="tag_id" value="<?php foreach ($rowss as $rows) {
                    echo $rows['tag_id'];
                } ?>">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="submit" value="編集" class="btn btn-primary">
            </form>

            <div class="deleat" onclick="return confirm('削除します。\nよろしいですか？');">
                <form action="" method="POST">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
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

