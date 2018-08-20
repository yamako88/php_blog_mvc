<?php
ini_set('display_errors', "On");

session_start();
require_once('../../model/SessionModel.php');
require_once('../../model/CategoryModel.php');

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


$error = [];
$error['category_name'] = '';

if (!empty($_POST)) {

    //    トークンチェック
    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);

        $category = htmlspecialchars($_POST['category_names'], ENT_QUOTES, 'utf-8');
        $category_id = $_POST['id'];

        //    バリデーション、重複チェック
        $categoryModel = new CategoryModel();
        $error = $categoryModel->validation_edit($session, $category);

        if (empty($error)) {

            $categoryModel = new CategoryModel();
            $update = $categoryModel->apdate($category_id, $category);

            header('Location: /category');
            exit();

        }
    }
//else文で閉じないと、エラーが起きる。ifじゃなかった時には空の文字列を入れておく。
} else {
    $error = [];
    $error['category_name'] = '';
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
        <ul class="navbar-nav mr-auto menu">
            <li class="nav-item">
                <a class="nav-link" href="/blog">記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/post">投稿 <span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item active menu__single">
                <a class="nav-link init-bottom" href="/category">カテゴリー</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/tag">タグ</a>
            </li>
            <li class="nav-item username">
                <a class="nav-lin　usename2">ようこそ、<?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'utf-8'); ?>
                    さん</a>
            </li>
        </ul>

        <div class="nav-item logout">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="/logout">ログアウト</a></button>

        </div>
    </div>
</nav>

<div class="menyu_title">
    <h1>カテゴリー編集</h1>
</div>


<form class="forms" action="" method="post">


    <div class="msr_text_02">
        <!--        <label>カテゴリー登録</label>-->
        <label>編集するカテゴリー名：<?php echo $_POST['category_name']; ?></label>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>"/>
        <input type="text" name="category_names"/>
        <?php if ($error['category_name'] == 'blank'): ?>
            <p class="error">*新しいカテゴリーを入力してください</p>
        <?php endif; ?>
        <?php if ($error['category_name'] == 'duplicate'): ?>
            <p class="error">*指定されたカテゴリーはすでに登録されています</p>
        <?php endif; ?>
    </div>


    <p class="msr_sendbtn_02">
        <input type="submit" value="登録する">
    </p>

</form>


</body>
</html>

