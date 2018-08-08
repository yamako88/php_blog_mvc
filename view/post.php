
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
    $statement->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);


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

    $statements = $dbh->prepare("SELECT * FROM tag where user_id = ?");
    //bindValueメソッドでパラメータをセット
    $statements->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);


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
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
<!--                <a class="nav-link" href="#">記事 <span class="sr-only">(current)</span></a>-->
                                <a class="nav-link" href="blog.php?page=1">記事</a>
            </li>
            <li class="nav-item active">
<!--                <a class="nav-link" href="#">投稿</a>-->
                <a class="nav-link" href="#">投稿 <span class="sr-only">(current)</span></a>

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
<h1>投稿</h1>
</div>



<!--投稿フォーム-->
<div class="menyu_title">

<form action="../index.php" method="POST">
    <p>タイトル</p>
    <input type = "text" name="title"><br>
    <p>テキスト</p>
    <textarea name ="text"></textarea><br/>

    <p>カテゴリー</p>
    <select name="category_id">
        <option>選択してください</option>
        <?php
        foreach($rows as $row) {
            ?>

            <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>

            <?php
        }

        ?>
    </select>


    <input type="hidden" name="eventId" value="save">

<!--    改行-->
    <p></p>

<!--    <form action="form_tag.php" method="POST">-->
        <p>タグ（複数回答可）:
            <?php
            foreach($rowss as $rows) {
                ?>

                <input type="checkbox" name="tags[]" value="<?php echo $rows['id']; ?>"><?php echo $rows['tag_name']; ?>

                <?php
            }

            ?>
        </p>
<!--        <p><input type="submit" value="送信"></p>-->
<!--    </form>-->


<br><br>
    <input type="submit" value="送信">

</form>




</div>





</body>
</html>
