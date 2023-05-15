<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログインページ</title>
</head>
<body>

  <?php
  function get_connection() {
    try{
      $dsn = 'mysql:dbname=dc_ec_site;host=localhost;charset=utf8';
      $login_user = 'root';
      $password = 'root';
      $dbh = new PDO($dsn,$login_user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      echo "接続成功";
    }catch (PDOException $e){
      echo $e->getMessage();
      exit();
    }
  }

  get_connection();
  ?>

  <h1>ログイン</h1>
  <form action="post" action="#">
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