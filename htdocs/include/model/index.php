<!-- login.phpの体裁で -->
<?php
  session_start();
?>

<?php
##定数
require_once '../config/const.php';
##DB接続
require_once 'db_connect.php';
##汎用系関数
require_once 'common.php';
?>

<?php
##Cookie時間
$cookie_time = time() + EXPIRATION_PERIOD * 60 * 24 * 365;

$post = h($_POST);
var_dump($post);
print '<br>';
##POST値代入
$user_name = $post['user_name'];
$password = $post['password'];

##DB参照
$select = 'SELECT * FROM ec_user WHERE user_name = ? AND password = ?';

$dbh = dbConnect();
$dbh->beginTransaction();

try{
  $stmt = $dbh->prepare($select);
  $stmt->bindValue(1,$user_name,PDO::PARAM_STR);
  $stmt->bindValue(2,$password,PDO::PARAM_STR);
  $stmt->execute();

//結果の取得
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  var_dump($result);
  print '<br>';

//結果を表示を$idに代入
  $_SESSION['login_id'] = (int)$result['user_id'];

  $dbh->commit();
} catch(PDOException $e) {
  echo $e->getMessage();
  $dbh->rollBack();
}

var_dump($_SESSION['login_id']);
print '<br>';

if(empty($_SESSION['login_id'])) {
  $login_err = "ユーザー名かパスワードが間違っています";
  header('Location:../../ec_site/index.php?err_m='.$login_err.'');
  exit();
} else {
  header('Location:../../ec_site/index.php');
  exit();
}



?>