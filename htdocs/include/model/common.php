<?php 
 echo "common.php";
 print "<br>";

/**
 * PHPDoc
 * POST値の値を受け取りサニタイズ処理の結果を返す
 * @param string
 * @return array
 */

  function h ($before) {
    foreach($before as $key => $value) {
      $after[$key] = htmlspecialchars($value,ENT_QUOTES,'UTF-8');
    }
    return $after;
  }

/**
 * ユーザー名バリデーション関数
 */

  function preg_str($str_int) {
    if($str_int == 1) { //遷移先の実引数参照
      $str = "ユーザー名は5文字以上";
      header('Location:../../ec_site/user.php?error='.$str.'');
      exit();
    }
    if($str_int == 2) { //遷移先の実引数参照
      $str = "ユーザー名は半角英数字";
      header('Location:../../ec_site/user.php?error='.$str.'');
      exit();
    }
  }

/**
 * パスワードバリデーション関数
 */
  function preg_pass($pass_int) {
    if($pass_int == 1) {
      $str = "8文字以上で入力して";
      header('Location:../../ec_site/user.php?error='.$str.'');
      exit();
    }
    if($pass_int == 2) {
      $str = "半角英数で頼むは";
      header('Location:../../ec_site/user.php?error='.$str.'');
      exit();
    }
  }

?>