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
    echo 'セッションが切れました。ログインし直してください。';
    exit();
}
?>

<?php

//投稿を削除する

$del = $pdo->prepare('DELETE FROM category WHERE id=?');
$del->bindValue(1, $_POST['id']);
$del->execute();



header('Location: category.php');
exit();




?>

