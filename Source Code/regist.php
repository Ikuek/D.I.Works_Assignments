<?php

// セッションスタート
session_cache_expire(0);
session_cache_limiter('private_no_expire');
session_start();
session_regenerate_id();

if(!empty($_SESSION['authority'])){
    $authority = $_SESSION['authority'];
}

//流れ
//入力、確認ボタン押下後にPOSTでデータを送る


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
    //エラーチェック↓
    $errors = array(); 

    // ページ戻ってきたときにデータ維持
    if (!empty($_SESSION['member'])) {
        $_POST = $_SESSION['member'];
    
    } elseif ( !empty($_POST)&& empty($_SESSION['member']) ) {

        //名前チェック
        if (empty($_POST['family_name'])) {
            $errors['family_name'] = '名前（姓）が未入力です。';
        }
        if (empty($_POST['first_name'])) {
            $errors['first_name'] = "名前（名）が未入力です。";
        }
        if (empty($_POST['family_name_kana'])) {
            $errors['family_name_kana'] = "カナ（姓）が未入力です。";
        } elseif(!preg_match("/^[ァ-ヾ]+$/u",$_POST['family_name_kana'])) {
            $errors['family_name_kana'] = "カタカナで入力してください。";
        }
        if (empty($_POST['first_name_kana'])) {
            $errors['first_name_kana'] = "カナ（名）が未入力です。";
        } elseif (!preg_match("/^[ァ-ヾ]+$/u",$_POST['first_name_kana'])) {
            $errors['first_name_kana'] = "カタカナで入力してください。";
        }

        // メールアドレス未入力＆形式チェック
        if (empty($_POST['mail'])) {
            $errors['mail'] = "メールアドレスが未入力です。";
        } elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])) {
            $errors['mail'] = "半角英数字、半角記号（ハイフン、アットマーク）のみ入力可能です。";
        }

        // パスワード未入力＆文字数チェック
        if (empty($_POST['password'])) {
            $errors['password'] = "パスワードを入力してください。";
        } elseif (preg_match("/^[a-zA-Z0-9]{1,10}$/", $_POST['password'])===0) {
            $errors['password'] = "パスワードは半角英数字10文字以内で入力してください。";
        }
        
        //郵便番号チェック
        if (preg_match('/^\d{7}$/',$_POST['postal_code'])===0){
            $errors['postal_code'] = "半角数字7文字で入力してください。";
        }

        // 都道府県未入力チェック
        if (empty($_POST['prefecture'])) {
            $errors['prefecture'] = "都道府県を選択してください。";
        }
        // 住所未入力チェック
        if (empty($_POST['address_1'])) {
            $errors['address_1'] = "未入力です。";
        }
        if (empty($_POST['address_2'])) {
            $errors['address_2'] = "未入力です。";
        }

        //エラー内容チェック -- エラーがなければregist_confirm.phpへリダイレクト
        if (empty($errors)) {
            $_SESSION['member'] = $_POST;
            $_SESSION['member_login'] = 1;
            header('Location: regist_confirm.php');
            exit();
        }
    }

} catch (Exception $e) {
    echo "<span style=\"color:red\">エラーが発生したためアカウント登録できません。". $e->getMessage()."</span>";
    exit();
}

//session_destroy();

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント登録</title>
    <link rel="stylesheet" type="text/css" href="account.css">
</head>
<body>
<header>
    <h1>アカウント登録画面</h1>
</header>
    <main>
    <form action="regist.php" method="POST" class="form">
        <p>
            アカウント登録画面
        </p>
        <table class="TableStyle">
            <tr>
                    <th>名前（姓）</th>
                    <td><input type="text" name="family_name" 
                        value="<?php echo isset($_POST['family_name']) ? htmlspecialchars($_POST['family_name'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['family_name']) ? $errors['family_name'] : ''; ?></td>
                </tr>
                <tr>
                    <th>名前（名）</th>
                    <td><input type="text" name="first_name" 
                        value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['first_name']) ? $errors['first_name'] : ''; ?></td>
                </tr>
                <tr>
                    <th>カナ（姓）</th>
                    <td><input type="text" name="family_name_kana" 
                        value="<?php echo isset($_POST['family_name_kana']) ? htmlspecialchars($_POST['family_name_kana'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['family_name_kana']) ? $errors['family_name_kana'] : ''; ?></td>
                </tr>
                <tr>
                    <th>カナ（名）</th>
                    <td><input type="text" name="first_name_kana" 
                        value="<?php echo isset($_POST['first_name_kana']) ? htmlspecialchars($_POST['first_name_kana'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['first_name_kana']) ? $errors['first_name_kana'] : ''; ?></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><input type="text" name="mail" 
                        value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['mail']) ? "<td class='error'>".$errors['mail'] ."</td>" : ''; ?></td>
                </tr>
                <tr>
                    <th>パスワード</th>
                    <td><input type="password" name="password"></td>
                    <td class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></td>
                </tr>
                <tr>
                    <th>性別</th>
                    <td><input type="radio" name="gender" value="0" checked class="textarea" <?php if(isset($_POST['gender']) && $_POST['gender']=="0") echo "checked";?>>男
                        <input type="radio" name="gender" value="1" class="textarea" <?php if(isset($_POST['gender']) && $_POST['gender']=="1") echo "checked";?>>女</td>
                </tr>
                <tr>
                    <th>郵便番号</th>
                    <td><input type="text" name="postal_code" 
                        value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['postal_code']) ? $errors['postal_code'] : '';?></td>
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
                        }?>
                        </select></td>
                    <td class="error"><?php echo isset($errors['prefecture']) ? $errors['prefecture'] : '';?></td>
                </tr>
                <tr>
                    <th>住所（市区町村）</th>
                    <td><input type="text" name="address_1" 
                        value="<?php echo isset($_POST['address_1']) ? htmlspecialchars($_POST['address_1'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['address_1']) ? $errors['address_1'] : '';?></td>
                </tr>
                <tr>
                    <th>住所（番地）</th>
                    <td><input type="text" name="address_2" 
                        value="<?php echo isset($_POST['address_2']) ? htmlspecialchars($_POST['address_2'],ENT_QUOTES) : ''; ?>" 
                        autocomplete="OFF"></td>
                    <td class="error"><?php echo isset($errors['address_2']) ? $errors['address_2'] : '';?></td>
                </tr>
                <tr>
                    <th>アカウント権限</th>
                    <td>
                        <select name="authority" class="textarea">
                            <option value="0" <?php echo array_key_exists('authority', $_POST) && $_POST['authority'] == '0' ? 'selected' : ''; ?>>一般</option>
                            <option value="1" <?php echo array_key_exists('authority', $_POST) && $_POST['authority'] == '1' ? 'selected' : '';?>>管理者</option>
                        </select>
                    </td>
                </tr>
        </table>
        <div class="buttons">
            <button type="submit" class="btn">確認する</button>
        </div>
    </form>
    </main>
</body>
</html>
