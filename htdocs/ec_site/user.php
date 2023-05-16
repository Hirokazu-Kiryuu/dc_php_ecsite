<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <title>ユーザーページ</title>
</head>
<body>

<?php


?>
  <h1>ユーザー登録</h1>
  <form method="post" action="../include/model/user_check.php">
    <p>ユーザー名
      <input type="text" name="user_name">
    </p>
    <p>パスワード
      <input type="text" name="password">
    </p>
    <input type="submit" name="login" value="登録">
  </form>
</body>
</html>