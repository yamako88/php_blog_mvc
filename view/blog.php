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
} else {
//    ログインしていない
    header('Location: ../login.php');
    exit();
}
?>





<?php

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


    $page = $_REQUEST['page'];
    if ($page == '') {
        $page = 1;
    }
    $page = max($page, 1);

//最終ページを取得する
    $counts = $dbh->prepare('SELECT COUNT(*) AS cnt FROM submission_form where user_id = ?');
    $counts->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
    $counts->execute();
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt'] / 5);
    $page = min($page, $maxPage);

    $start = ($page - 1) * 5;




    $statement = $dbh->prepare('SELECT * FROM (SELECT * FROM submission_form where user_id = ? order by id desc limit ?, 5) as formcategory left outer join category ON category.category_id = formcategory.category_id;');
    $statement->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
    $statement->bindValue(2, $start, PDO::PARAM_INT);
    $statement->execute();
    $rows = $statement->fetchAll();
    $dbh = null;

} catch (PDOException $e) {
    // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
    // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
// Webブラウザにこれから表示するものがUTF-8で書かれたHTMLであることを伝える
// (これか <meta charset="utf-8"> の最低限どちらか1つがあればいい． 両方あっても良い．)
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
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <!--                <a class="nav-link" href="#">記事 <span class="sr-only">(current)</span></a>-->
                <a class="nav-link" href="#">記事</a>
            </li>
            <li class="nav-item">
                <!--                <a class="nav-link" href="#">投稿</a>-->
                <a class="nav-link" href="post.php">投稿 <span class="sr-only">(current)</span></a>

            </li>
            <li class="nav-item">
                <a class="nav-link" href="category.php">カテゴリー</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tag.php">タグ</a>
            </li>
            <li class="nav-item username">
                <a class="nav-lin　usename2">ようこそ、<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>さん</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="search.php" method="get">
            <input class="form-control mr-sm-2" type="text" name="search" value="" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索</button>
        </form>

        <div class="nav-item logout">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="../logout.php">ログアウト</a></button>

        </div>
    </div>
</nav>

<div class="menyu_title">
<h1>記事</h1>
</div>


<?php
ini_set('display_errors', "On");
foreach($rows as $row) {

    ?>

<?php

    ini_set('display_errors', "On");

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

//    select * from (SELECT * FROM submission_form where user_id = 5) as forms join category ON category.category_id = forms.category_id left join form_tag on form_tag.form_id = forms.id left join tag on tag.id = form_tag.tag_id;

    $statements = $dbh->prepare("SELECT * FROM (SELECT * FROM tag where user_id = ?) as formtag inner join (SELECT * FROM form_tag where form_id = ?) as formid ON formid.tag_id = formtag.id;");
    //bindValueメソッドでパラメータをセット
    $statements->bindValue(1, $_SESSION['id']);
    $statements->bindValue(2, $row['id']);


    //executeでクエリを実行
    $statements->execute();
    $rowss = $statements->fetchAll();

    ?>


    <div class="card">
        <?php
            $text = $row['text'];
        ?>

        <div class="card-body">
            <h3 class="card-title"><?php echo $row['title']; ?></h3>
            <p class="card-text">
                <?php echo nl2br($text); ?>
            </p>
            <p>投稿日時：<?php echo $row['date']; ?></p>
            <p>カテゴリー：<?php echo $row['category_name']; ?></p>

            <p>タグ：<?php foreach($rowss as $rows) {echo $rows['tag_name']; echo "　";} ?></p>

            <form action="form_edit.php" method="post">
                <input type="hidden" name="title" value="<?php echo $row['title']; ?>">
                <input type="hidden" name="text" value="<?php echo $text; ?>">
                <input type="hidden" name="category_name" value="<?php echo $row['category_name']; ?>">
                <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                <input type="hidden" name="tag_id" value="<?php foreach($rowss as $rows) {echo $rows['tag_id'];} ?>">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="submit" value="編集" class="btn btn-primary">
            </form>

            <div class="deleat"  onclick="return confirm('削除します。\nよろしいですか？');">
                <form action="form_delete.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="submit" value="削除" class="btn btn-primary">
                </form>
            </div>

        </div>


</div>
    <?php
}
?>


<ul>
    <?php
    if ($page > 1) {
    ?>

    <a href="blog.php?page=<?php echo($page - 1); ?>">前へ</a>

    <?php
    }else {
        ?>

<!--        <li></li>-->

        <?php
    }
    ?>
    <a>　<?php echo($page); ?>ページ目　</a>
    <?php
    if ($page < $maxPage) {
        ?>

        <a href="blog.php?page=<?php echo($page + 1); ?>">次へ</a>

        <?php
    }else {
        ?>

<!--        <li></li>-->

        <?php
    }
    ?>
</ul>





</body>
</html>
