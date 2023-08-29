<?php
//セッション開始
session_start();
var_dump($_SESSION);
print '<br>';
?>

<?php
//定数
require_once '../config/const.php';
//DB接続
require_once 'model.php';
//汎用系関数
require_once 'common.php';
?>

<?php

##Cookieの時間
$cookie_time = time() + EXPIRATION_PERIOD * 60 * 24 * 365;

$post = h($_POST);
var_dump($post);
print '<br>';
##index.phpからの受け取り値
$user_name = $post['user_name'];
$password = $post['password'];

##ログインエラーメッセージ
$login_err = LOGIN_ERR;
##ログインTRUEメッセージ
$login_true = LOGIN_TRUE;

##DB参照(実質バリデーションも込み込み)
$sql = 'SELECT * FROM ec_user WHERE user_name = ? AND password = ?';

$dbh = dbConnect();
$dbh->beginTransaction();

try{
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(1,$user_name,PDO::PARAM_STR);
  $stmt->bindValue(2,$password,PDO::PARAM_STR);
  $stmt->execute();

//結果の取得
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

//結果を表示を$idに代入
  $id = (int)$result['user_id'];
  $dbh->commit();
} catch(PDOException $e) {
  echo $e->getMessage();
  $dbh->rollBack();
}

##session
if($id === 0) {
echo "仮止め:ログインエラー<br>";
  // $_SESSION['err_flg'] = TRUE;
  header('Location:../../ec_site/index.php?massage='.$login_err.'');
  exit();
}

if($id === 1) {
//Sessionに値埋め込み
  $_SESSION['login_id'] = $id;
  // echo "管理者<br>";
  header('Location:../../ec_site/product_con.php');
  exit();
}

if($id > 1) {
  $_SESSION['login_id'] = $id;
  // echo "ユーザー<br>";
  header('Location:../../ec_site/product_list.php');
  exit();
}

var_dump($id);
print'<br>';
var_dump($_SESSION);
print'<br>';

?>