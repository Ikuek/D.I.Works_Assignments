<?php

session_start();
try {

    if (isset($_POST['login'])) {

        $mail = filter_input(INPUT_POST, 'mail');
        $pass = filter_input(INPUT_POST, 'password');
        
        //エラーチェック
        $err = [];
        if($mail == ''){
            $err['mail'] = 'メールアドレスを入力してください。';
        }
        if($pass == ''){
            $err['pass'] = 'パスワードを入力してください。';
        }

        
        //エラーなければ
        if(count($err) == 0) {

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
                //var_dump($$password);

                if(password_verify($pass,$password)) {
                    //ログインしているかの判断のため↓
                    $_SESSION['member_login'] = 1;
                    $_SESSION['family_name'] = $row['family_name'];
                    $_SESSION['authority'] = $row['authority'];
                    header('Location:d.i.blog.php');
                    exit();
                }
            }
            
            $err['login'] = 'メールアドレスかパスワードに誤りがあります。';
            $_SESSION = array();
            session_destroy();
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
<link rel="stylesheet" type="text/css" href="account.css">
</head>
<body>
    <header>
        <h1>ログイン画面</h1>
    </header>
    <form action="login.php" method="POST" class="form">
        <p>ログイン画面</p>
            <?php if(isset($err['login'])):?>
                <td class="error"><?php echo $err['login'];?></td>
            <?php endif;?>

        <table class="TableStyle">
            <tr>
                <td class="login"><label>メールアドレス</label></td>
                <td><input type="text" name="mail"></td>
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
        </table>
        <div  class="buttons">
            <button type="submit" name="login">ログイン</button>
        </div>
    </form>
</body>
</html>
