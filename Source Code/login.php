<?php

session_start();
try {

    if (isset($_POST['login'])) {

        $mail = filter_input(INPUT_POST, 'mail');
        $pass = filter_input(INPUT_POST, 'password');
        
        //未入力チェック
        //$err = [];
        if($mail == ''){
            $err['mail'] = 'メールアドレスを入力してください。';
        }
        if($pass == ''){
            $err['pass'] = 'パスワードを入力してください。';
        }

        
        //入力あれば
        if(!isset($err)) {

            mb_internal_encoding("UTF-8");
            //DB接続
            $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // SQL作成
            $sql = "SELECT * FROM member WHERE mail= ?";
            $stmt = $pdo->prepare($sql);

            //パラメーターの受け取り
            $data[] = $mail;
            
            $stmt->execute($data);
            $rows = $stmt->fetchAll();

            foreach ($rows as $row){
                $password = $row['password'];
                $email = $row['email'];

                if(password_verify($pass,$password)) {
                    //ログインしているかの判断のため↓
                    $_SESSION['member_login'] = 1;
                    $_SESSION['family_name'] = $row['family_name'];
                    $_SESSION['authority'] = $row['authority'];
                    header('Location:d.i.blog.php');
                    exit();
                }
            }

            if (!isset($email)||($password)) {
                $err['login'] = 'メールアドレスかパスワードに誤りがあります。';
            }

        }
    }

} catch (Exception $e) {
    echo "<span style=\"color:red\">エラーが発生したためログイン情報を取得できません。". $e->getMessage()."</span>";
    exit();
}
        
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログイン画面</title>
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
            <h1>ログイン画面</h1>
                <?php if(isset($err['login'])):?>
                    <div class="error"><?php echo $err['login'];?></div>
                <?php endif;?>

            <div class="TableStyle">
                <form action="login.php" method="POST">
                    <table>
                        <tr>
                            <td class="login"><label>メールアドレス</label></td>
                            <td><input type="text" name="mail" value="<?php echo isset($_POST['mail'])? $mail : '';?>"></td>
                            <?php if(isset($err['mail'])):?>
                                <td class="error"><?php echo $err['mail'];?></td>
                            <?php endif;?>
                        </tr>
                        <tr>
                            <td class="login"><label>パスワード</label></td>
                            <td><input type="password" name="password"></td>
                            <?php if(isset($err['pass'])):?>
                                <td class="error"><?php echo $err['pass'];?></td>
                            <?php endif;?>
                        </tr>
                        <tr> 
                            <td></td>
                            <td><button type="submit" name="login">ログイン</button></td>
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
