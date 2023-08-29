<?php
session_start();
?>

<?php
//ヘッダー
require_once '../include/template/header.php';
//定数
require_once '../include/config/const.php';
//SESSION系関数
require_once '../include/model/session.php';
//DB接続
require_once '../include/model/model.php';
//汎用系関数
require_once '../include/model/common.php';
?>

<?php

##ログインしていない状態ならばセッションを切る
if(empty($_SESSION)) {
  $session = session_name();
##Cookie上のセッションを削除
  setcookie($session,'',time() -30, '/');
}

##一覧ページリダイレクト
// list_redirect($_SESSION['login_id']);

##POST_サニタイズ関数
$post = h($_POST);

##デバックエリア 
$session = session_name();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <link rel="stylesheet" href="../include/css/common.css">
  <title>ログインページ</title>
</head>
<body>
<div class="text_center">
  <h1>ログイン</h1>
  <?php echo "<p class=error>".$_GET['massage']."</p>"?>
  <form method="post" action="../include/model/login.php">
    <p>ユーザー名
      <input type="text" name="user_name">
    </p>
    <p>パスワード
<!-- type=passwordに変更 -->
      <input type="text" name="password">
    </p>
    <input type="submit" name="login" class="common_btn" value="ログイン">
  </form>
  <br>
  <a href="user.php">新規登録ページへ</a>
</div>
</body>
</html>