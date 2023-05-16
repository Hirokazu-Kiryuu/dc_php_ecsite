<?php
#定数
  require_once '../include/config/const.php';
#DB接続
  require_once '../include/model/model.php';
#汎用系関数
  require_once '../include/model/common.php';
#header関数
  require_once '../include/template/header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <title>ログインページ</title>
</head>
<body>
  <?php

  $post = h($_POST);
//確認用h()
  var_dump($post);
  print "<br>";
//

  get_connection();
  ?>

  <h1>ログイン</h1>
  <form method="post" action="index.php">
    <p>ユーザー名
      <input type="text" name="user_name" value="">
    </p>
    <p>パスワード
      <input type="text" name="password" value="">
    </p>
    <input type="submit" name="login" value="登録">
    <br>
  </form>
  <a href="user.php">新規登録ページへ</a>


</body>
</html>