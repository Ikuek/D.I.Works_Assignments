<?php
session_start();
session_regenerate_id();

//直アクセスと一般権限はd.i.blog.phpへリダイレクト
if (isset($_SESSION['member_login'])===false || $_SESSION['authority']==0){
    header('Location: d.i.blog.php');
    exit();
}

try {

    mb_internal_encoding("UTF-8");
    //DB接続
    $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // SQL作成＆実行
    $stmt = $pdo->query("SELECT * FROM member ORDER BY id desc");

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
<link rel="stylesheet" type="text/css" href="account.css">
</head>
    <body>
        <header>
            <h1>アカウント一覧画面</h1>
        </header>
        <main>
            <div class="form">
                <p>アカウント一覧画面</p>
                
                <table class="TableList">
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

                    <?php while($row = $stmt->fetch()):?>
                    <tr>
                        <td><?= $row['id'];?></td>
                        <td><?= $row['family_name'];?></td>
                        <td><?= $row['first_name'];?></td>
                        <td><?= $row['family_name_kana'];?></td>
                        <td><?= $row['first_name_kana'];?></td>
                        <td><?= $row['mail']?></td>
                        <td><?php if($row['gender']==='0'): ?>
                                <?="男"?>
                            <?php else : ?>
                                <?="女"?>
                            <?php endif; ?></td>
                        <td><?php if($row['authority']==='0'): ?>
                                <?="一般"?>
                            <?php else : ?>
                                <?="管理者"?>
                            <?php endif; ?></td>
                        <td><?php if($row['delete_flag']==='0'): ?>
                                <?="有効"?>
                            <?php else : ?>
                                <?="無効"?>
                            <?php endif; ?></td>
                        <td><?= date("m/d/Y",strtotime($row['registered_time']));?></td>
                        <td><?php if(!isset($row['update_time'])): ?>
                                <?= date("m/d/Y",strtotime($row['registered_time']));?>
                            <?php else : ?>
                                <?= date("m/d/Y",strtotime($row['update_time']));?>
                            <?php endif; ?></td>
                        <td>
                            <form action="update.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']?>">
                                <input type="submit" value="更新">
                            </form>
                        </td>
                        <td>
                            <form action="delete.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']?>">
                                <input type="submit" value="削除">
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            
                <div  class="buttons">
                    <a href="d.i.blog.php">
                        <button type="button">TOPページへ戻る</button>
                    </a>
                </div>
            </div>
        </main>
    </body>
</html>