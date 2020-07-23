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

    $_POST = $_SESSION['member'];
    $member = array(
        $family_name = $_POST['family_name'],
        $first_name = $_POST['first_name'],
        $family_name_kana = $_POST['family_name_kana'],
        $first_name_kana = $_POST['first_name_kana'],
        $mail = $_POST['mail'],
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT),
        $gender = (int)$_POST['gender'],
        $postal_code = (int)$_POST['postal_code'],
        $prefecture = $_POST['prefecture'],
        $address_1 = $_POST['address_1'],
        $address_2 = $_POST['address_2'],
        $authority = (int)$_POST['authority']
    );

    mb_internal_encoding("UTF-8");
    $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO member(family_name,first_name,family_name_kana,first_name_kana,mail,password,gender,postal_code,prefecture,address_1,address_2,authority) VALUE(?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($member);

} catch (Exception $e) {
    echo "<span style=\"color:red\">エラーが発生したためアカウント登録できません。". $e->getMessage()."</span>";
    exit();
}
session_destroy();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アカウント登録完了</title>
<link rel="stylesheet" type="text/css" href="account.css">
</head>
    <body>
        <header>
            <h1>アカウント登録完了画面</h1>
        </header>
        <main>
            <div class="form">
                <p>アカウント登録確認画面</p>
                <div class="complete">
                    <p>登録完了しました</p>
                </div>
            
                <div  class="buttons">
                    <a href="d.i.blog.php">
                        <button type="button" class="btn">TOPページへ戻る</button>
                    </a>
                </div>
            </div>
        </main>
    </body>
</html>