<?php
session_start();
if(!empty($_SESSION['authority'])){
    $authority = $_SESSION['authority'];
}

    unset($_POST);
    unset($_SESSION['member']);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>DI Blog</title>
<link rel="stylesheet" type="text/css" href="stylediblog.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

  <script>
    $(document).ready(function(){
        $('.slider').bxSlider({
            auto:true,
            mode:'horizontal',
            speed:2000
        });
    });
  </script>
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
                <div class="left">
                    <h1>プログラミングに役立つ書籍</h1>
                    <p>2017年1月15日</p>
                    <div class="slider">
                        <div><img src="jQuery_image1.jpg"></div>
                        <div><img src="jQuery_image2.jpg"></div>
                        <div><img src="jQuery_image3.jpg"></div>
                        <div><img src="jQuery_image4.jpg"></div>
                        <div><img src="jQuery_image5.jpg"></div>
                    </div>
                    <p>D.I.BlogはD.I.Worksが提供する演習課題です。</p>
                    <h3>記事中身</h3>
                    <ul class="itemlist">
                        <li>
                            <img src="pic1.jpg">
                            <dl>ドメイン取得方法</dl>
                        </li>
                        <li>
                            <img src="pic2.jpg">
                            <dl>快適な職場環境</dl>
                        </li>
                        　<li>
                            <img src="pic3.jpg">
                            <dl>Linuxの基礎</dl>
                        </li>
                        <li>
                            <img src="pic4.jpg">
                            <dl>マーケティング入門</dl>
                        </li>
                        <li>
                            <img src="pic5.jpg">
                            <dl>アクティブラーニング</dl>
                        </li>
                        <li>
                            <img src="pic6.jpg">
                            <dl>CSSの効率的な勉強方法</dl>
                        </li>
                        　<li>
                            <img src="pic7.jpg">
                            <dl>リーダブルコードとは</dl>
                        </li>
                        <li>
                            <img src="pic8.jpg">
                            <dl>HTML5の可能性</dl>
                        </li>
                    </ul>
                </div>
                <div class="right">
                    <h3>人気の記事</h3>
                        <ul>
                            <li>PHPオススメ本</li>
                            <li>PHP　MyAdminの使い方</li>
                            <li>今人気のエディタTops</li>
                            <li>HTMLの基礎</li>
                        </ul>
                    <h3>オススメリンク</h3>
                        <ul>
                            <li>ディーアイワークス株式会社</li>
                            <li>XAMPPのダウンロード</li>
                            <li>Eclipseのダウンロード</li>
                            <li>Braketsのダウンロード</li>
                        </ul>
                    <h3>カテゴリ</h3>
                        <ul>
                            <li>HTML</li>
                            <li>PHP</li>
                            <li>MySQL</li>
                            <li>JavaScript</li>
                        </ul>
                </div>
            </div>
        </main>
        <footer>
            Copyright D.I.works| D.I. blog is the one which provides A to Z about programming
        </footer>
    </body>
</html>