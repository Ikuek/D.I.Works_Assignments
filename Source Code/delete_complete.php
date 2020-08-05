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

try {

    $id = $_SESSION['id'];
    $delete_flag = $_SESSION['delete_flag'];
    if ($delete_flag==='0'){
        $delete_flag = 1;
    }

    mb_internal_encoding("UTF-8");

    $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;","root","root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE member SET delete_flag=$delete_flag WHERE id=$id";
    $stmt = $pdo->query($sql);

} catch (Exception $e) {
    echo "<span style=\"color:red\">エラーが発生したためアカウント削除できません。</br>". $e->getMessage()."</span>";
    exit();
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アカウント削除完了</title>
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
                <h1>アカウント削除完了画面</h1>

                <div class="TableStyle">
                    <div class="complete">
                        <p>削除完了しました</p>
                    </div>
                    <div class="complete">
                        <a href="d.i.blog.php">
                            <button type="button">TOPページへ戻る</button>
                        </a>
                    </div>
                    
                        
                </div>
            </div>
        </main>
            <footer>
            Copyright D.I.works| D.I. blog is the one which provides A to Z about programming
            </footer>
</body>
</html>