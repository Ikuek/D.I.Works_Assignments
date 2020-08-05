<?php

// セッションスタート
session_cache_expire(0);
session_cache_limiter('private_no_expire');
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

    //DBから更新前データを取得、表示
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $_SESSION['id'] = $id;
    }
    
        $id = $_SESSION['id']; //戻るボタン押下時にセッションから変数に代入(query実行の為)
        mb_internal_encoding("UTF-8");
        //DB接続
        $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // SQL作成＆実行
        $stmt = $pdo->query("SELECT * FROM member WHERE id='$id'");
        
        $row = $stmt->fetch();
        while ($row===TRUE) {
            $row = $stmt->fetch();
        }

} catch (Exception $e) {
    echo "<span style=\"color:red\">エラーが発生しました。". $e->getMessage()."</span>";
    exit();
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アカウント削除</title>
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
            <h1>アカウント削除画面</h1>
            <div class="TableStyle">
                <form action="delete_confirm.php" method="POST">
                    <input type="hidden" name="delete_flag" value="<?php echo $row['delete_flag']?>">
                    <table>
                        <tr>
                            <td><label>名前（姓）</label></td>
                            <td><?= $row['family_name']; ?></td>
                        </tr>
                        <tr>
                            <td><label>名前（名）</label></td>
                            <td><?= $row['first_name']; ?></td>
                        </tr>
                        <tr>
                            <td><label>カナ（姓）</label></td>
                            <td><?= $row['family_name_kana']; ?></td>
                        </tr>
                        <tr>
                            <td><label>カナ（名）</label></td>
                            <td><?= $row['first_name_kana']; ?></td>
                        </tr>
                        <tr>
                            <td><label>メールアドレス</label></td>
                            <td><?= $row['mail']; ?></td>
                        </tr>
                            <td><label>パスワード</label></td>
                            <td><?= "●●●●●●●●";?></td>
                        </tr>
                        <tr>
                            <td><label>性別</label></td>
                            <td><?php if ($row['gender']==="0") echo "男"; ?>
                                <?php if ($row['gender']==="1") echo "女"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><label>郵便番号（ハイフンなし）</label></td>
                            <td><?= $row['postal_code']; ?></td>
                        </tr>
                        <tr>
                            <td><label>住所（都道府県）</label></td>
                            <td><?= $row['prefecture']; ?></td>
                        <tr>
                            <td><label>住所（市区町村）</label></td>
                            <td><?= $row['address_1']; ?></td>
                        </tr>
                        <tr>
                            <td><label>住所（番地）</label></td>
                            <td><?= $row['address_2']; ?></td>
                        </tr>
                        <tr>
                            <td><label>アカウント権限</label></td>
                            <td><?php 
                                if ($row['authority'] === "0"){
                                    echo "一般";
                                } else { echo "管理者";} ?>
                            </td>
                        </tr>
                        <tr> 
                            <td></td>
                            <td><button type="submit" name="clicked">確認する</button></td>
                        </tr>
                    </table>
                </form>   
            </div>
        </div>
    </main>
    <footer>
        Copyright D.I.works| D.I. blog is the one which provides A to Z about programming
    </footer>
</body>
</html>
