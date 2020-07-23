<?php 

session_start();

//直アクセスと一般権限はd.i.blog.phpへリダイレクト
if (isset($_SESSION['member_login'])===false || $_SESSION['authority']==0){
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
<link rel="stylesheet" type="text/css" href="account.css">
</head>
    <body>
        <header>
            <h1>アカウント削除確認画面</h1>
        </header>
        <main>
            <form action="delete_complete.php" method="POST" class="form">
                <p>アカウント削除確認画面</p>
                <div class="complete">
                    <p>本当に削除してよろしいですか？</p>
                </div>
            
                <div class="buttons">
                    <a href="delete.php">
                        <button type="button" class="btn">前に戻る</button>
                    </a>
                    <button type="submit" class="btn">削除する</button>
                </div>
            </form>
        </main>
    <footer></footer>
    </body>
</html>