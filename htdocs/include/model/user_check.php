<?php
#定数
  require_once '../config/const.php';
#DB＆SQL系
  require_once 'model.php';
#汎用系関数
  require_once 'common.php';

#POSTサニタイズ
  $post = h($_POST);
  $user_name = $post['user_name'];
  $password = $post['password'];

#user_name バリデーション
  if (strlen($user_name) <= 4) {
    $str_int = 1;//preg関数の実引数の参照値
    echo $str_int;
    preg_str($str_int);
  } elseif(!preg_match('/^[a-zA-Z0-9]+$/', $user_name)) {
    $str_int = 2;
    echo $str_int;
    preg_str($str_int);
  } else {
    echo "DB登録";
    print"<br>";
    var_dump($user_name);
    print"<br>";
  }

#password バリデーション
  if (strlen($password) <= 7) {
    $pass_int = 1;//preg関数の実引数の参照値
    echo $pass_int;
    preg_pass($pass_int);
  } elseif(!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    $pass_int = 2;
    echo $pass_int;
    preg_pass($pass_int);
  } else {
    echo "DB登録";
    print"<br>";
    var_dump($password);
    print"<br>";
  }

?>

<!-- 
・strlen
引数に指定した文字列の長さを調べる
 -->

