<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/common.css">

  <title>ログインページ</title>
</head>
<body>
  <div class="text_center">
    <h1>ログイン</h1>
    <?php echo "<p class=error>".$_GET['err_m']."</p>"?>
    <br><br>
    <form action="../include/model/index.php" method="POST">
      <label for="user_name">ユーザー名
        <input type="text" name="user_name" id="user_name">
      </label>
    <br><br>
      <label for="password">パスワード
        <input type="text" name="password" id="password">
      </label>
    <br><br>
      <input type="submit" name="login" class="common_btn" value="ログイン">
    </form>
    <br>
    <a href="../ec_site/user.php">新規登録ページへ</a>
  </div>
</body>
</html>