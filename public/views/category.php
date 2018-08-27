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

//カテゴリー一覧表示
$categoryModel = new CategoryModel();
$rows = $categoryModel->select_category($session);


$error = [];

if (!empty($_POST['category_name'])) {

    //    トークンチェック
    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);

        $category = htmlspecialchars($_POST['category_name'], ENT_QUOTES, 'utf-8');

//    バリデーション、重複チェック
        $categoryModel = new CategoryModel();
        $error = $categoryModel->validation($session, $category);

//    カテゴリー登録
        if (empty($error)) {
            $categoryModel = new CategoryModel();
            $add = $categoryModel->insert_category($session, $category);

            header('Location: /category');
            exit();
        }
    }
//else文で閉じないと、エラーが起きる。ifじゃなかった時には空の文字列を入れておく。
} else {
    $error = [];
    $error['category_name'] = '';
}


//投稿を削除する
if (!empty($_POST['delete'])) {

//    トークンチェック
    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);

        $delete = $_POST['delete'];

        $categoryModel = new CategoryModel();
        $rows = $categoryModel->delete_category($delete);

        header('Location: /category');
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
        <ul class="navbar-nav mr-auto menu">
            <li class="nav-item">
                <a class="nav-link" href="blog.php?page=1">記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/post">投稿</a>

            </li>

            <li class="nav-item active menu__single">
                <a class="nav-link init-bottom" href="#">カテゴリー</a>
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
    <h1>カテゴリー</h1>
</div>


<form class="forms" action="" method="POST">


    <div class="msr_text_02">
        <label>カテゴリー登録</label>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="text" name="category_name"/>
        <?php if ($error['category_name'] == 'blank'): ?>
            <p class="error">*カテゴリーを入力してください</p>
        <?php endif; ?>
        <?php if ($error['category_name'] == 'duplicate'): ?>
            <p class="error">*指定されたカテゴリーはすでに登録されています</p>
        <?php endif; ?>
    </div>


    <p class="msr_sendbtn_02">
        <input type="submit" value="登録する">
    </p>

</form>


<?php
foreach ($rows as $row) {
    ?>

    <div class="card">

        <div class="card-body">
            <h3 class="card-title"><?php echo $row['category_name']; ?></h3>

            <form action="category_edit.php" method="post">
                <input type="hidden" name="category_name_now" value="<?php echo $row['category_name']; ?>">
                <input type="hidden" name="id" value="<?php echo $row['category_id']; ?>">
                <input type="submit" value="編集" class="btn btn-primary">
            </form>

            <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="delete" value="<?php echo $row['category_id']; ?>">
                <input type="submit" value="削除" class="btn btn-primary" onclick="return confirm('削除します。\nよろしいですか？');">
            </form>
        </div>

    </div>


    </div>
    <?php
}
?>

</body>
</html>
