<?php
##定数
require_once '../config/const.php';
##DB接続
require_once 'db_connect.php';
##汎用系関数
require_once 'common.php';
?>

<?php
##POST値代入
$post = h($_POST);
var_dump($post);
print '<br>';
$user_name = $post['user_name'];
$password = $post['password'];
##日付関数
$date = today();
##正規表現
$str_preg = '/^[a-zA-Z0-9]+$/';

##バリデーション
if(mb_strlen($user_name) <= 4) {
  $err[] = "ユーザー名は5文字以上にしてください";
  header('Location:../../ec_site/user.php?err_m='.$err[0].'');
  exit();
}

if(!preg_match($str_preg,$user_name)) {
  $err[] = "ユーザー名は半角英数のみ入力可能です";
  header('Location:../../ec_site/user.php?err_m='.$err[0].'');
  exit();
}

if(mb_strlen($password) <= 7) {
  $err[] = "パスワードは8文字以上にしてください";
  header('Location:../../ec_site/user.php?err_m='.$err[0].'');
  exit();
}

if(!preg_match($str_preg,$password)) {
  $err[] = "パスワードは半角英数のみ入力可能です";
  header('Location:../../ec_site/user.php?err_m='.$err[0].'');
  exit();
}

var_dump($err);
print '<br>';

##DB登録処理
if(count($err) === 0) {
  echo "DB登録処理<br>";
##登録massage変数
  $m = [];
  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    $user_insert = 'INSERT INTO ec_user
    (user_name,password,create_date,update_date)
    VALUES(?,?,?,?)';

    $stmt = $dbh->prepare($user_insert);

    // var_dump($stmt);

    $stmt->bindValue(1,$user_name);
    $stmt->bindValue(2,$password);
    $stmt->bindValue(3,$date);
    $stmt->bindValue(4,$date);

    $stmt->execute();
    $dbh->commit();

    $m[] = $user_name."さんの登録が完了しました";
    header('Location:../../ec_site/user.php?register='.$m[0].'');
    exit();

  } catch (PDOException $e) {
    $dbh->rollBack();
    $m[] = "そのユーザー名は既に使用されています";
    header('Location:../../ec_site/user.php?err_m='.$m[0].'');
    exit();
  }
}




?>

