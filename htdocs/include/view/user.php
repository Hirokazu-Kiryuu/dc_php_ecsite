<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー登録</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/common.css">
</head>
<body>
  <div class="text_center">
    <h1>ユーザー登録</h1>
    <?php echo "<p class=error>".$_GET['register']."</p>"?>
    <?php echo "<p class=success>".$_GET['err_m']."</p>"?>
    <form action="../include/model/user.php" method="POST">
      <label for="user_name">ユーザー名
        <input type="text" name="user_name" id="user_name">
      </label>
      <br><br>
      <label for="password">パスワード
        <input type="text" name="password" id="password">
      </label>
      <br><br>
      <input type="submit" name="register" value="登録" class="common_btn">
    </form>
    <br>
    <a href="../ec_site/index.php">ログインページへ</a>
  </div>
  
</body>
</html>