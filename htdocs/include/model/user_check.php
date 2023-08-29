<?php
//定数
require_once '../config/const.php';
//DB＆SQL系
require_once 'model.php';
//汎用系関数
require_once 'common.php';
?>

<?php

$post = h($_POST);
$user_name = $post['user_name'];
$password = $post['password'];
##今日の日付
$date = today();

##バリデーション
##エラー判定配列
$err = [];
##正規表現変数
$str_pattern = '/^[a-zA-Z0-9]+$/';
##ユーザー登録バリデーション
if(mb_strlen($user_name) <= 4) {
$err[] = "ユーザー名は5文字以上入力してください";
header('Location:../../ec_site/user.php?error='.$err[0].'');
exit();
}

if(!preg_match($str_pattern,$user_name)) {
  $err[] = "ユーザー名は半角英数字のみ入力可能です";
  header('Location:../../ec_site/user.php?error='.$err[0].'');
  exit();
}

if(mb_strlen($password) <= 7) {
  $err[] = "パスワードは8文字以上入力してください";
  header('Location:../../ec_site/user.php?error='.$err[0].'');
  exit();
}

if(!preg_match($str_pattern,$password)) {
  $err[] = "パスワードは半角英数字のみ入力可能です";
  header('Location:../../ec_site/user.php?error='.$err[0].'');
  exit();
}

##データベース登録処理
if(count($err) === 0) {
//データベース処理のメッセージ変数
  $m = [];
  echo "DB登録処理"."<br>";

  $sql = 'INSERT INTO ec_user
  (user_name, password, create_date,update_date) 
  VALUES
  (:user,:pass,:create,:update)';

  $dbh = dbConnect();
  $dbh->beginTransaction();

  try {

  $stmt = $dbh->prepare($sql);

##パスワードをbind時にハッシュ化(現状バグる)
  // $pass_h = password_hash($password,PASSWORD_DEFAULT);
  // $stmt->bindValue(':pass',$pass_h);
  $stmt->bindValue(':user',$user_name);
  $stmt->bindValue(':pass',$password);
  $stmt->bindValue(':create',$date);
  $stmt->bindValue(':update',$date);

  $stmt->execute();

  $dbh->commit();
  $m[] = $user_name."さん"."<br>"."登録完了しました";
  header('Location:../../ec_site/user.php?register='.$m[0].'');
  exit();

  } catch(PDOException $e) {
    $dbh->rollBack();
    $m[] = "そのユーザー名はすでに登録されています";
    header('Location:../../ec_site/user.php?error='.$m[0].'');
    exit();
  }
} 
?>