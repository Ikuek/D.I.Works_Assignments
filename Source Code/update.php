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
    
    //エラーチェック
    $errors = array();

    //DBから更新前データを取得し、テキストボックスに初期値として表示
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $_SESSION['id'] = $id;

        mb_internal_encoding("UTF-8");
        //DB接続
        $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // SQL作成＆実行
        $stmt = $pdo->query("SELECT * FROM member WHERE id='$id'");
        
        $row = $stmt->fetch();
        $_SESSION['row'] = $row['mail'];
            var_dump($_SESSION['row']);
        /*while ($row===TRUE) {
            $row = $stmt->fetch();
        }*/

    }

    //確認するボタン押下後のエラーチェック
    if(isset($_POST['clicked'])) {

        //名前チェック
        if (empty($_POST['family_name'])) {
            $errors['family_name'] = '名前（姓）が未入力です。';
        } elseif (10 < mb_strlen($_POST['family_name'])) {
            $errors['family_name'] = '10文字以内で入力してください。';
        }
        if (empty($_POST['first_name'])) {
            $errors['first_name'] = "名前（名）が未入力です。";
        } elseif (10 < mb_strlen($_POST['first_name'])) {
            $errors['first_name'] = '10文字以内で入力してください。';
        }
        if (empty($_POST['family_name_kana'])) {
            $errors['family_name_kana'] = "カナ（姓）が未入力です。";
        } elseif(!preg_match("/^[ァ-ヾ]+$/u",$_POST['family_name_kana'])) {
            $errors['family_name_kana'] = "カタカナで入力してください。";
        } elseif (10 < mb_strlen($_POST['family_name_kana'])) {
            $errors['family_name_kana'] = '10文字以内で入力してください。';
        }
        if (empty($_POST['first_name_kana'])) {
            $errors['first_name_kana'] = "カナ（名）が未入力です。";
        } elseif (!preg_match("/^[ァ-ヾ]+$/u",$_POST['first_name_kana'])) {
            $errors['first_name_kana'] = "カタカナで入力してください。";
        } elseif (10 < mb_strlen($_POST['first_name_kana'])) {
            $errors['first_name_kana'] = '10文字以内で入力してください。';
        }
        //登録済みメールアドレスチェック(初期値と異なるときにチェック)
        if ($_SESSION['row']!==$_POST['mail']) { 
            $email = $_POST['mail'];
            //DB接続
            $pdo = new PDO("mysql:dbname=lesson1; hostname=localhost8888;" ,"root","root");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // SQL作成
            $sql = "SELECT count(*) FROM member WHERE mail= ?";
            $stmt = $pdo->prepare($sql);
        
            $stmt->execute([$email]);
            $count = (int)$stmt->fetchColumn();

            if ($count) {
                $errors['mail'] = "このメールアドレスはすでに登録されています。";
            }
        }
        
        // メールアドレス未入力＆形式チェック
        if (empty($_POST['mail'])) {
            $errors['mail'] = "メールアドレスが未入力です。";
        } elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])) {
            $errors['mail'] = "半角英数字、半角記号（ハイフン、アットマーク）のみ入力可能です。";
        } elseif (100 < mb_strlen($_POST['mail'])) {
            $errors['mail'] = '100文字以内で入力してください。';
        }
        
        // パスワード未入力＆文字数チェック
        if (empty($_POST['password'])) {
            $errors['password'] = "パスワードを入力してください。";
        } elseif (preg_match("/^[a-zA-Z0-9]{1,10}$/", $_POST['password'])===0) {
            $errors['password'] = "パスワードは半角英数字10文字以内で入力してください。";
        }
        
        //郵便番号チェック
        if (empty($_POST['postal_code']) || (preg_match('/^\d{7}$/',$_POST['postal_code'])===0)) {
            $errors['postal_code'] = "半角数字7文字で入力してください。";
        }
        
        // 都道府県未入力チェック
        if (empty($_POST['prefecture'])) {
            $errors['prefecture'] = "都道府県を選択してください。";
        }
        // 住所未入力チェック
        if (empty($_POST['address_1'])) {
            $errors['address_1'] = "未入力です。";
        } elseif (10 < mb_strlen($_POST['address_1'])) {
            $errors['address_1'] = '10文字以内で入力してください。';
        }
        if (empty($_POST['address_2'])) {
            $errors['address_2'] = "未入力です。";
        } elseif (100 < mb_strlen($_POST['address_2'])) {
            $errors['address_2'] = '100文字以内で入力してください。';
        }
        
        //エラーがなければupdate_confirm.phpへリダイレクト
        if (count($errors)===0) {
            unset($_POST['clicked']);
            $_SESSION['member'] = $_POST;
            header('Location:update_confirm.php');
            exit();
        }
    }
    // ページ戻ってきたときにデータ維持
    elseif (isset($_SESSION['member'])) {
        $_POST = $_SESSION['member'];
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
<title>アカウント更新</title>
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
            <h1>アカウント更新画面</h1>
            <div class="TableStyle">
                <form action="update.php" method="POST">
                    <table>
                        <tr>
                            <th>名前（姓）</th>
                            <td><input type="text" name="family_name" 
                                value="<?php echo !empty($row) ? $row['family_name'] : htmlspecialchars($_POST['family_name'],ENT_QUOTES); ?>"></td>
                            <?php echo isset($errors['family_name']) ? "<td class='error'>".$errors['family_name']."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>名前（名）</th>
                            <td><input type="text" name="first_name" 
                                value="<?php echo !empty($row) ? $row['first_name'] : htmlspecialchars($_POST['first_name'],ENT_QUOTES) ; ?>" 
                                autocomplete="OFF"></td>
                            <?php echo isset($errors['first_name']) ? "<td class='error'>".$errors['first_name']."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>カナ（姓）</th>
                            <td><input type="text" name="family_name_kana" 
                                value="<?php echo !empty($row) ? $row['family_name_kana'] : htmlspecialchars($_POST['family_name_kana'],ENT_QUOTES); ?>" 
                                autocomplete="OFF"></td>
                            <?php echo isset($errors['family_name_kana']) ? "<td class='error'>".$errors['family_name_kana'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>カナ（名）</th>
                            <td><input type="text" name="first_name_kana" 
                                value="<?php echo !empty($row) ? $row['first_name_kana'] : htmlspecialchars($_POST['first_name_kana'],ENT_QUOTES); ?>" 
                                autocomplete="OFF"></td>
                            <?php echo isset($errors['first_name_kana']) ? "<td class='error'>".$errors['first_name_kana'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>メールアドレス</th>
                            <td><input type="text" name="mail" 
                                value="<?php echo !empty($row) ? $row['mail'] : htmlspecialchars($_POST['mail'],ENT_QUOTES); ?>" 
                                autocomplete="OFF"></td>
                            <?php echo isset($errors['mail']) ? "<td class='error'>".$errors['mail'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>パスワード</th>
                            <td><input type="password" name="password"></td>
                            <?php echo isset($errors['password']) ? "<td class='error'>".$errors['password'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td>
                                <input type="radio" name="gender" value="0" class="textarea" 
                                    <?php if((isset($_POST['gender']) && $_POST['gender']=="0") || (isset($row['gender']) && $row['gender']=="0")) echo "checked";?>>男
                                <input type="radio" name="gender" value="1" class="textarea"
                                    <?php if((isset($_POST['gender']) && $_POST['gender']=="1") || (isset($row['gender']) && $row['gender']=="1")) echo "checked";?>>女</td>
                        </tr>
                        <tr>
                            <th>郵便番号</th>
                            <td><input type="text" name="postal_code" 
                                value="<?php echo !empty($row) ? $row['postal_code'] : htmlspecialchars($_POST['postal_code'],ENT_QUOTES); ?>" 
                                ></td>
                            <?php echo isset($errors['postal_code']) ? "<td class='error'>".$errors['postal_code'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>都道府県</th>
                            <td><select name="prefecture"  class="textarea">
                                <option></option>
                                <?php $pref_list = array('北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
                                '茨城県','栃木県','群馬県', '埼玉県','千葉県', '東京都', '神奈川県','新潟県',' 富山県',
                                '石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県',
                                '京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県',
                                '山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県',
                                '大分県','宮崎県','鹿児島県','沖縄県');?>
                                
                                <?php foreach($pref_list as $value) {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                }
                                if (isset($_POST['prefecture'])){
                                    echo '<option value="' .$_POST['prefecture'].'" selected>'.htmlspecialchars($_POST['prefecture'],ENT_QUOTES). '</option>';
                                } else {
                                    echo '<option value="' .$row['prefecture'].'" selected>'.$row['prefecture'].'</option>';
                                }?>
                                </select></td>
                            <?php echo isset($errors['prefecture']) ? "<td class='error'>".$errors['prefecture'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>住所（市区町村）</th>
                            <td><input type="text" name="address_1" 
                                value="<?php echo !empty($row) ? $row['address_1'] : htmlspecialchars($_POST['address_1'],ENT_QUOTES); ?>" 
                                autocomplete="OFF"></td>
                            <?php echo isset($errors['address_1']) ? "<td class='error'>".$errors['address_1'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>住所（番地）</th>
                            <td><input type="text" name="address_2" 
                                value="<?php echo !empty($row) ? $row['address_2'] : htmlspecialchars($_POST['address_2'],ENT_QUOTES); ?>" 
                                autocomplete="OFF"></td>
                            <?php echo isset($errors['address_2']) ? "<td class='error'>".$errors['address_2'] ."</td>" : ''; ?>
                        </tr>
                        <tr>
                            <th>アカウント権限</th>
                            <td>
                                <select name="authority" class="textarea">
                                    <option value="0" <?php if((isset($_POST['authority']) && $_POST['authority']== "0") || (isset($row['authority']) && $row['authority']=="0")) echo 'selected'; ?> >一般</option>
                                    <option value="1" <?php if((isset($_POST['authority']) && $_POST['authority']== "1") || (isset($row['authority']) && $row['authority']=="1")) echo 'selected'; ?> >管理者</option>
                                </select>
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
