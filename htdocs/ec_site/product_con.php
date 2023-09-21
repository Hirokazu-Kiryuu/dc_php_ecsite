<?php 
session_start();
##SESSION系
require_once '../include/model/session.php';

##リダイレクト処理
var_dump($_SESSION['login_id']);
print '<br>';

##TOPページへ強制リダイレクト
index_redirect($_SESSION['login_id']);
##商品一覧ページへ強制リダイレクト
list_redirect($_SESSION['login_id']);

?>

<?php
##定数
require_once '../include/config/const.php';
##DB接続
require_once '../include/model/db_connect.php';
##DB系関数
require_once '../include/model/sql.php';
##汎用系関数
require_once '../include/model/common.php';
##view_logic
require_once '../include/view/product_con_disp.php';
##view
require_once '../include/view/product_con.php';
?>