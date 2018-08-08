
<?php
ini_set('display_errors', "On");

session_start();
//require('../connect2.php');

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

// エラーを出力する
ini_set('display_errors', "On");

    /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */

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

$_SESSION['join'] = $_POST;

$text = htmlspecialchars($_POST["text"]);
//    投稿を記録する
    $stmt = $pdo->prepare('insert into submission_form (title, text, date, category_id, user_id) values(:title, :text, now(), :category_id, :user_id)');
    $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $stmt->bindParam(':category_id', $_POST['category_id'], PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_STR);

    $stmt->execute();
//var_dump($pdo->lastInsertId('id'));
$tagform = $pdo->lastInsertId('id');
//    投稿を記録する(中間テーブル)
$tags = $_POST["tags"];
//var_dump($tags);
//$titles = $_POST["titles"];
//$len = mb_strlen($titles,"utf-8");

foreach ($tags as $val) {
    $stmt = $pdo->prepare('insert into form_tag (form_id, tag_id) values(:form_id, :tag_id)');
    $stmt->bindParam(':form_id', $tagform, PDO::PARAM_STR);
    $stmt->bindParam(':tag_id', $val, PDO::PARAM_STR);
    $stmt->execute();

}





unset($_SESSION['join']);

header('Location: /view/blog.php?page=1');
exit();

    echo '投稿しました';
    /* データベースから値を取ってきたり， データを挿入したりする処理 */


?>
