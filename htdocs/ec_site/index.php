<?php
session_start();
?>

<?php
##ヘッダー
require_once '../include/template/header.php';
##view
require_once '../include/view/index.php';
?>

<?php
##デバック
  var_dump($_SESSION['login_id']);
  print '<br>';

##ログインIDによって遷移先の振り分け
  if($_SESSION['login_id'] === 1) {
    // echo "管理者画面";
    header('Location:product_con.php');
    exit();
  } elseif($_SESSION['login_id'] >= 2) {
    header('Location:product_list.php');
    exit();
  } 

?>