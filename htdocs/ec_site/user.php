<?php
session_start();
?>

<?php
//ヘッダー部品
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
// list_redirect($_SESSION['login_id']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <link rel="stylesheet" href="../include/css/common.css">
  <title>ユーザーページ</title>
</head>
<body>
  <div class="text_center">
    <h1>ユーザー登録</h1>
    <?php echo "<p class=error>".$_GET['error']."</p>"?>
    <?php echo "<p class=success>".$_GET['register']."</p>"?>
    <form method="post" action="../include/model/user_check.php">
      <p>ユーザー名
        <input type="text" name="user_name">
      </p>
      <p>パスワード
        <input type="text" name="password">
      </p>
      <input type="submit" name="login" class="common_btn" value="登録">
    </form>
    <br>
    <a href="index.php">ログイン画面へ</a>
  </div>
</body>
</html>