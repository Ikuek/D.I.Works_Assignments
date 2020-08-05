<?php
session_start();
session_regenerate_id();

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

    if (isset($_GET['search'])) {
        $search[] = "";
        $family_name = filter_input(INPUT_GET, 'family_name');
        $first_name = filter_input(INPUT_GET, 'first_name');
        $family_name_kana = filter_input(INPUT_GET, 'family_name_kana');
        $first_name_kana = filter_input(INPUT_GET, 'first_name_kana');
        $mail = filter_input(INPUT_GET, 'mail');
        $gender = filter_input(INPUT_GET, 'gender');
        $authority = filter_input(INPUT_GET, 'authority');

        mb_internal_encoding("UTF-8");
        //DB接続
        $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($search)){
            $sql = "SELECT * FROM member WHERE family_name LIKE '%$family_name%'
                AND first_name LIKE '%$first_name%'
                AND family_name_kana LIKE '%$family_name_kana%'
                AND first_name_kana LIKE '%$first_name_kana%'
                AND mail LIKE '%$mail%'
                AND gender LIKE '%$gender%'
                AND authority LIKE '%$authority%' ORDER BY id desc";
            
            $stmt = $pdo->query($sql);
        } else {
            $stmt = $pdo->query("SELECT * FROM member ORDER BY id desc");
        }
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
<title>アカウント一覧</title>
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
        </ul>
    </header>
        <main>
            <div class="main-container">
            <h1>アカウント一覧画面</h1>
                <div class="TableList">
                    <form id="search" action="list.php" method="GET">
                    <table class="ListTable">
                        <tr>
                            <th>名前（姓）</th>
                            <th><input type="text" name="family_name" class="TextBox" value="<?php echo isset($_GET['family_name']) ? htmlspecialchars($_GET['family_name'],ENT_QUOTES) : ''; ?>"></th>
                            <th>名前（名）</th>
                            <th><input type="text" name="first_name" class="TextBox" value="<?php echo isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name'],ENT_QUOTES) : ''; ?>"></th>
                        </tr>
                        <tr>
                            <th>カナ（姓）</th>
                            <th><input type="text" name="family_name_kana" class="TextBox"　value="<?php echo isset($_GET['family_name_kana']) ? htmlspecialchars($_GET['family_name_kana'],ENT_QUOTES) : ''; ?>"></th>
                            <th>カナ（名）</th>
                            <th><input type="text" name="first_name_kana" class="TextBox"　value="<?php echo isset($_GET['first_name_kana']) ? htmlspecialchars($_GET['first_name_kana'],ENT_QUOTES) : ''; ?>"></th>
                        </tr>
                        <tr>
                            <th>メールアドレス</th>
                            <th><input type="text" name="mail" class="TextBox" value="<?php echo isset($_GET['mail']) ? htmlspecialchars($_GET['mail'],ENT_QUOTES) : ''; ?>"></th>
                            <th>性別</th>
                            <th>
                                <input type="radio" name="gender" value="0" <?php if(isset($_GET['gender']) && $_GET['gender']=="0") echo "checked";?>>男
                                <input type="radio" name="gender" value="1" <?php if(isset($_GET['gender']) && $_GET['gender']=="1") echo "checked";?>>女
                            </th>
                        </tr>
                        <tr>
                            <th>アカウント権限</th>
                            <th>
                                <select name="authority" class="textarea">
                                    <option></option>
                                    <option value="0" <?php echo array_key_exists('authority', $_GET) && $_GET['authority'] == '0' ? 'selected' : ''; ?>>一般</option>
                                    <option value="1" <?php echo array_key_exists('authority', $_GET) && $_GET['authority'] == '1' ? 'selected' : '';?>>管理者</option>
                                </select>
                            </th>
                            <th colspan="2">
                                <button type="submit" name="search" method="GET">検索</button>
                            </th>
                        </tr>
                    </table>
                    </form>
    
                    <?php if(isset($_GET['search'])) { ?>
                        <table class="ListTable">
                            <tr>
                                <th>ID</th>
                                <th>名前（姓）</th>
                                <th>名前（名）</th>
                                <th>カナ（姓）</th>
                                <th>カナ（名）</th>
                                <th>メールアドレス</th>
                                <th>性別</th>
                                <th>アカウント権限</th>
                                <th>削除フラグ</th>
                                <th>登録日時</th>
                                <th>更新日時</th>
                                <th colspan="2">操作</th>
                            </tr>
                        
                            <?php while($row = $stmt->fetch()){ ?>
                            <tr>
                                <td><?= $row["id"];?></td>
                                <td><?= $row["family_name"];?></td>
                                <td><?= $row["first_name"];?></td>
                                <td><?= $row["family_name_kana"];?></td>
                                <td><?= $row["first_name_kana"];?></td>
                                <td><?= $row["mail"]?></td>
                                <td><?php if($row["gender"]==="0"): ?>
                                        <?="男"?>
                                    <?php else : ?>
                                        <?="女"?>
                                    <?php endif; ?></td>
                                <td><?php if($row["authority"]==="0"): ?>
                                        <?="一般"?>
                                    <?php else : ?>
                                        <?="管理者"?>
                                    <?php endif; ?></td>
                                <td><?php if($row["delete_flag"]==="0"): ?>
                                        <?="有効"?>
                                    <?php else : ?>
                                        <?="無効"?>
                                    <?php endif; ?></td>
                                <td><?= date("m/d/Y",strtotime($row["registered_time"]));?></td>
                                <td><?php if(!isset($row["update_time"])): ?>
                                        <?= date("m/d/Y",strtotime($row["registered_time"]));?>
                                    <?php else : ?>
                                        <?= date("m/d/Y",strtotime($row["update_time"]));?>
                                    <?php endif; ?></td>
                                <td>
                                    <form action="update.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row["id"]?>">
                                        <input type="submit" value="更新">
                                    </form>
                                </td>
                                <td>
                                    <form action="delete.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row["id"]?>">
                                        <input type="submit" value="削除">
                                    </form>
                                </td>
                            </tr>
                            
                        <?php } } ?>
                                
                    </table>
                </div>
            </div>
        </main>
        <footer>
            Copyright D.I.works| D.I. blog is the one which provides A to Z about programming
        </footer>
    </body>
</html>