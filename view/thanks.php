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
            <!--            <li class="nav-item">-->
            <!--                <!--                <a class="nav-link" href="#">記事 <span class="sr-only">(current)</span></a>-->
            <!--                <a class="nav-link" href="/view/blog.php">記事</a>-->
            <!--            </li>-->
            <!--            <li class="nav-item active">-->
            <!--                <!--                <a class="nav-link" href="#">投稿</a>-->
            <!--                <a class="nav-link" href="/view/post.php">投稿 <span class="sr-only">(current)</span></a>-->

            <!--            </li>-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link" href="/view/category.php">カテゴリー</a>-->
            <!--            </li>-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link" href="/view/tag.php">タグ</a>-->
            <!--            </li>-->
        </ul>
        <!--        <form class="form-inline my-2 my-lg-0">-->
        <!--            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">-->
        <!--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
        <!--        </form>-->

        <div class="nav-item logout">
            <a  href="/login.php">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">ログイン</button>
            </a>
        </div>
    </div>
</nav>

<div class="menyu_title">
    <h2>ユーザー登録が完了しました</h2>
</div>

<p class="menyu_title"><a href="../login.php">ログインする</a></p>

















</body>
</html>
