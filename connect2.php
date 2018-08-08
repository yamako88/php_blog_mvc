<?php
$db = mysqli_connect('localhost', 'root', 'root', 'php_blog') or
    die(mysqli_connect_error());
mysqli_set_charset($db, 'utf8');
//ini_set('display_errors', "On");



//try {
//
//    /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */
//
//    // データベースに接続
//    $db = new PDO(
//        'mysql:dbname=php_blog;host=localhost;charset=utf8mb4',
//        'root',
//        'root',
//        [
////            エラーの設定
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//        ]
//    );
//
//}catch (PDOException $e) {

    // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
    // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
//    header('Content-Type: text/plain; charset=UTF-8', true, 500);
//    exit
//    ($e->getMessage());
//        echo 'DB接続エラー：' . $e->getMessage();
//}


?>


<?php
//try {
//    $db = new PDO('mysql:dbname=php_blog;host=127.0.0.1;charset=utf8','root', 'root');
//} catch (PDOException $e) {
//    echo 'DB接続エラー： ' . $e->getMessage();
//}
//?>
