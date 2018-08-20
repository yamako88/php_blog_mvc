<?php

ini_set('display_errors', "On");

session_start();
require_once('../../model/SessionModel.php');
require_once('../../model/PostModel.php');
require_once('../../model/BlogeditModel.php');


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
$rows = $postModel->category($session);

//タグ表示
$postModel = new PostModel();
$rowss = $postModel->tag($session);


//選択してるタグの表示
if (!empty($_POST['edit'])) {

    $postid = $_POST['id'];

    $blogeditModel = new BlogeditModel();
    $forms = $blogeditModel->selecttag($session, $postid);

}

//投稿アップデート
if (!empty($_POST['update'])) {

//    トークンチェック
    if (isset($_POST['token'], $_SESSION['token']) && ($_POST['token'] === $_SESSION['token'])) {
        unset($_SESSION['token']);

        $title = htmlspecialchars($_POST["title"], ENT_QUOTES, 'utf-8');
        $text = htmlspecialchars($_POST["text"], ENT_QUOTES, 'utf-8');
        $category_id = $_POST['category_id'];
        $form_id = $_POST['id'];

        $blogeditModel = new BlogeditModel();
        $update = $blogeditModel->update($session, $title, $text, $category_id, $form_id);

        header('Location: /blog');
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
            <li class="nav-item active">
                <a class="nav-link" href="/blog">記事</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/post">投稿 <span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item menu__single">
                <a class="nav-link init-bottom" href="/category">カテゴリー</a>
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
    <h1>記事編集</h1>
</div>


<!--投稿フォーム-->
<div class="menyu_title">

    <form action="" method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="update" value="sample">
        <p>タイトル</p>
        <input type="text" name="title" placeholder="<?php echo $_POST['title']; ?>"
               value="<?php echo $_POST['title']; ?>"><br>
        <p>テキスト</p>
        <textarea name="text" placeholder=""><?php echo $_POST['text']; ?></textarea><br/>

        <p>カテゴリー</p>
        <select name="category_id">

            <option value="<?php echo $_POST['category_id']; ?>"><?php echo $_POST['category_name']; ?></option>
            <?php
            foreach ($rows as $row) {
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

            foreach ($rowss as $tag) {

                $keyindex = array_search($tag['id'], array_column($forms, 'tag_id'));
                $result = $forms[$keyindex];

                if ($result["tag_id"] == $tag['id']) {
                    ?>

                    <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>"
                           checked="checked"><?php echo $tag['tag_name']; ?>

                <?php } else { ?>


                    <input type="checkbox" name="tags[]"
                           value="<?php echo $tag['id']; ?>"><?php echo $tag['tag_name']; ?>


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

