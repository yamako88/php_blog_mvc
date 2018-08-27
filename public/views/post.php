<?php
ini_set('display_errors', "On");

session_start();
require_once('../../model/SessionModel.php');
require_once('../../service/validation/PostValidation.php');
require_once('../../model/PostModel.php');

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


//カテゴリー表示
$postModel = new PostModel();
$rows = $postModel->select_category($session);

//タグ表示
$postModel = new PostModel();
$rowss = $postModel->select_tag($session);

$error = [];

//記事登録
if (!empty($_POST)) {

//    トークンチェック
    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);


//    エラー項目の確認
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'utf-8');
        $text = htmlspecialchars($_POST['text'], ENT_QUOTES, 'utf-8');
        $category_id = htmlspecialchars($_POST['category_id'], ENT_QUOTES, 'utf-8');
        if (!empty($_POST['tags'])) {
            $tags = $_POST['tags'];
        } else {
            $tags = [];
        }


        $postValidation = new PostValidation();
        $error = $postValidation->addValidation($title, $text, $category_id, $tags);

//    nullだけを取ってくる
        $errors = array_filter($error, 'strlen');

        if (empty($errors)) {

            $postModel = new PostModel();
            $post = $postModel->insert($title, $text, $category_id, $tags, $session);

            header('Location: /blog');
            exit();
        }
    }

} else {
    $error = [];
    $error['title'] = '';
    $error['text'] = '';
    $error['category_id'] = '';
    $error['tags'] = '';
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
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/blog">記事</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">投稿</a>

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


        <div class="nav-item logout">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="/logout">ログアウト</a></button>

        </div>
    </div>
</nav>


<div class="menyu_title">
    <h1>投稿</h1>
</div>


<!--投稿フォーム-->
<div class="menyu_title">

    <form action="" method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <p>タイトル</p>
        <input type="text" name="title"><br>
        <?php if ($error['title'] == 'blank'): ?>
            <p class="error">*タイトルを入力してください</p>
        <?php endif; ?>

        <p>テキスト</p>
        <textarea name="text"></textarea><br/>
        <?php if ($error['text'] == 'blank') { ?>
            <p class="error">*テキストを入力してください</p>
        <?php } elseif ($error['text'] == null) { ?>
            <p></p>
        <?php } ?>

        <p>カテゴリー</p>
        <select name="category_id">
            <option>選択してください</option>
            <?php
            foreach ($rows as $row) {
                ?>

                <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>

                <?php
            }
            ?>
        </select>


        <input type="hidden" name="eventId" value="save">
        <?php if ($error['category_id'] == 'blank'): ?>
            <p class="error">*カテゴリーを入力してください</p>
        <?php endif; ?>

        <!--    改行-->
        <p></p>

        <p>タグ（複数回答可）:
            <?php
            foreach ($rowss as $rows) {
                ?>

                <input type="checkbox" name="tags[]" value="<?php echo $rows['id']; ?>"><?php echo $rows['tag_name']; ?>

                <?php
            }
            ?>
        </p>

        <?php if ($error['tags'] == 'blank'): ?>
            <p class="error">*タグを入力してください</p>
        <?php endif; ?>

        <br><br>
        <input type="submit" value="送信">

    </form>

</div>


</body>
</html>
