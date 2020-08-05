<?php 

session_start();

//直アクセスと一般権限はd.i.blog.phpへリダイレクト
if (isset($_SESSION['member_login'])==false ){
    header('Location: d.i.blog.php');
    exit();
}
if($_SESSION['authority']==0){
    header('Location: d.i.blog.php');
    exit();
}

$_SESSION['delete_flag'] = $_POST['delete_flag']

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アカウント削除確認</title>
<link rel="stylesheet" type="text/css" href="stylediblog.css">
</head>
<body>
    <a href="d.i.blog.php">
        <img src="diblog_logo.jpg">
    </a>
    <header>
        <ul>
            <li><a href="d.i.blog.php">トップ</a></li>
            <li>プロフィール</li>
            <li>D.I.Blogについて</li>
            <li>登録フォーム</li>
            <li>問合せ</li>
            <li>その他</li>
            <?php if(isset($authority)&&($authority == 1)):?>
                <?= '<li><a href="list.php">アカウント一覧</a></li>';?>
                <?= '<li><a href="regist.php">アカウント登録</a></li>';?>
            <?php endif; ?>
            <?php if(isset($_SESSION['id']) === false):?>
                <?= '<li><a href="login.php">ログイン</a></li>';?>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <div class="main-container">
            <h1>アカウント削除確認画面</h1>
            
            <div class="TableStyle">
                <form action="delete_complete.php" method="POST">
                    <div class="complete">
                        <p>本当に削除してよろしいですか？</p>
                    </div>
                    <div class="complete">
                        <a href="delete.php">
                            <button type="button">前に戻る</button>
                        </a>

                        <button type="submit">削除する</button>
                    </div>
                </form>
            </div>
        </div>    
    </main>
    <footer>
        Copyright D.I.works| D.I. blog is the one which provides A to Z about programming
    </footer>
    </body>
</html>