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








    $stmts = $dbh->prepare('SELECT * FROM tag where user_id = ?');
    $stmts->bindParam(1, $_SESSION['id'], PDO::PARAM_STR);
    //                    $stmt->bindParam(':tag_id', $val, PDO::PARAM_STR);

    $stmts->execute();
    $tags = $stmts->fetchAll();


//
//    $stmts = $pdo->prepare('select * from form_tag where form_id=:form_id');
//    $stmts->bindParam(':form_id', $_POST['id'], PDO::PARAM_STR);
//    //                    $stmt->bindParam(':tag_id', $val, PDO::PARAM_STR);
//
//    $stmts->execute();
//    $tags = $stmts->fetchAll();

//    var_dump($rows['category_name']);

    $dbh = null;

} catch (PDOException $e) {

    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());

}

header('Content-Type: text/html; charset=utf-8');




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

//    $statements = $dbh->prepare("select form_id, group_concat(tag_id separator ',') as tag_id, group_concat(tag_name separator ',') as tag_name, user_id from (select * from form_tag where form_id=?) as form_tag left join (select * from tag where user_id=?)as tag on tag.id = form_tag.tag_id group by form_id");
//    $statements = $dbh->prepare("SELECT * FROM tag where user_id = ?");
    $statements = $dbh->prepare("select form_id,tag_id,tag_name, user_id from (select * from form_tag where form_id=?) as form_tag left join (select * from tag where user_id=?)as tag on tag.id = form_tag.tag_id");
    //bindValueメソッドでパラメータをセット
    $statements->bindValue(1, $_POST['id']);
    $statements->bindValue(2, $_SESSION['user_id']);


    //executeでクエリを実行
    $statements->execute();
    $rowss = $statements->fetchAll();
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
            <li class="nav-item active">
                <a class="nav-link" href="blog.php?page=1">記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="post.php">投稿 <span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item menu__single">
                <a class="nav-link init-bottom" href="category.php">カテゴリー</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tag.php">タグ</a>
            </li>
            <li class="nav-item username">
                <a class="nav-lin　usename2">ようこそ、<?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>さん</a>
            </li>
        </ul>

        <div class="nav-item logout">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="../logout.php">ログアウト</a></button>

        </div>
    </div>
</nav>

<div class="menyu_title">
    <h1>記事編集</h1>
</div>


<!--投稿フォーム-->
<div class="menyu_title">

    <form action="form_edit_index.php" method="POST">
        <p>タイトル</p>
        <input type = "text" name="title" placeholder="<?php echo $_POST['title']; ?>" value="<?php echo $_POST['title']; ?>"><br>
        <p>テキスト</p>
        <textarea name ="text" placeholder=""><?php echo $_POST['text']; ?></textarea><br/>

        <p>カテゴリー</p>
        <select name="category_id">

            <option value="<?php echo $_POST['category_id']; ?>"><?php echo $_POST['category_name']; ?></option>
            <?php
            foreach($rows as $row) {
                ?>

                <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>

                <?php
            }

            ?>
        </select>


        <input type="hidden" name="eventId" value="save">
        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">

        <!--    改行-->
        <p></p>




        <p>タグ（複数回答可）:




            <?php
            ini_set('display_errors', "On");

                foreach ($tags as $tag) {

                    $keyindex = array_search($tag['id'], array_column($rowss, 'tag_id'));
                    $result = $rowss[$keyindex];

                    if ($result["tag_id"] == $tag['id']) {
                        ?>

                        <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" checked="checked"><?php echo $tag['tag_name']; ?>

                    <?php } else { ?>


                        <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>"><?php echo $tag['tag_name']; ?>


                        <?php
                    }


            ?>
                <?php
            }
            ?>
        </p>


        <br><br>
        <input type="submit" value="送信">

    </form>




</div>








</body>
</html>

