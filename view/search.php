
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
ini_set('display_errors',1);
//require_once('db_info.php');


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


if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $search_value = $search;
}else {
    $search = '';
    $search_value = '';
}



//
//$pages = $_REQUEST['pages'];
//if ($pages == '') {
//    $pages = 1;
//}
//$pages = max($pages, 1);
//
////最終ページを取得する
//$counts = $pdo->prepare("SELECT COUNT(*) AS cnt FROM (SELECT * FROM submission_form where user_id = ?) as forms left join category ON category.category_id = forms.category_id left join (select form_id, tag_id, group_concat(tag_name separator ',') as tag_name, user_id from (select * from form_tag) as form_tag left join tag on tag.id = form_tag.tag_id group by form_id) as tags on tags.form_id = forms.id where CONCAT(title, text, date, category_name, tag_name) LIKE '%$search%'");
//$counts->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
//$counts->execute();
//$cnt = $counts->fetch();
//$maxPages = ceil($cnt['cnt'] / 5);
//$pages = min($pages, $maxPages);
//
//$start = ($pages - 1) * 5;


$statement = $pdo->prepare("select * from (SELECT * FROM submission_form where user_id = ? order by id desc) as forms 
left join category ON category.category_id = forms.category_id 
left join (select form_id, tag_id, group_concat(tag_name separator ',') as tag_name, user_id from (select * from form_tag) as form_tag 
left join tag on tag.id = form_tag.tag_id group by form_id) as tags on tags.form_id = forms.id 
where CONCAT(title, text, date, category_name, tag_name) LIKE '%$search%'");
//bindValueメソッドでパラメータをセット
$statement->bindValue(1, $_SESSION['id']);
$statement->execute();
$rows = $statement->fetchAll();






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
                <a class="nav-link" href="blog.php?page=1">記事</a>
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
            <input class="form-control mr-sm-2" type="text" name="search" value="<?php echo $search_value ?>" placeholder="Search" aria-label="Search">
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

//    $statements = $dbh->prepare("SELECT * FROM (SELECT * FROM tag where user_id = ?) as formtag inner join (SELECT * FROM form_tag where form_id = ?) as formid ON formid.tag_id = formtag.id;");
    $statements = $dbh->prepare("SELECT * FROM (SELECT * FROM tag where user_id = ?) as formtag join (SELECT * FROM form_tag where form_id = ?) as formid ON formid.tag_id = formtag.id;");
    //bindValueメソッドでパラメータをセット
    $statements->bindValue(1, $_SESSION['id']);
    $statements->bindValue(2, $row['id']);


    //executeでクエリを実行
    $statements->execute();
    $rowss = $statements->fetchAll();

    ?>


    <div class="card">



        <div class="card-body">
            <h3 class="card-title"><?php echo $row['title']; ?></h3>
            <p class="card-text">
                <?php echo $row['text']; ?>
            </p>
            <p>投稿日時：<?php echo $row['date']; ?></p>
            <p>カテゴリー：<?php echo $row['category_name']; ?></p>

            <p>タグ：<?php foreach($rowss as $rows) {echo $rows['tag_name']; echo "　";} ?></p>

            <form action="form_edit.php" method="post">
                <input type="hidden" name="title" value="<?php echo $row['title']; ?>">
                <input type="hidden" name="text" value="<?php echo $row['text']; ?>">
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

<?php
//var_dump($cnt);
//?>

<!--<ul>-->
<!--    --><?php
//    if ($pages > 1) {
//        ?>
<!---->
<!--        <a href="search.php?search=--><?php //echo($search); ?><!--?page=--><?php //echo($pages - 1); ?><!--">前へ</a>-->
<!---->
<!--        --><?php
//    }else {
//        ?>
<!---->
<!--        <!--        <li></li>-->
<!---->
<!--        --><?php
//    }
//    ?>
<!--    <a>　--><?php //echo($pages); ?><!--ページ目　</a>-->
<!--    --><?php
//    if ($pages < $maxPages) {
//        ?>
<!---->
<!--        <a href="search.php?search=--><?php //echo($search); ?><!--?pages=--><?php //echo($pages + 1); ?><!--">次へ</a>-->
<!---->
<!--        --><?php
//    }else {
//        ?>
<!---->
<!--        <!--        <li></li>-->
<!---->
<!--        --><?php
//    }
//    ?>
<!--</ul>-->





</body>
</html>

