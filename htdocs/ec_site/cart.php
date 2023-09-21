<?php
//デバックコード
session_start();

var_dump($_SESSION);
print'<br>';
require_once '../include/model/session.php';
index_redirect($_SESSION['login_id']);


?>

<?php
$form_display = TRUE;
##ヘッダー部品
require_once '../include/template/header.php';
##定数
require_once '../include/config/const.php';
##DB接続
require_once '../include/model/db_connect.php';
##DB系関数
require_once '../include/model/sql.php';
##汎用系関数
require_once '../include/model/common.php';
##view
require_once '../include/view/cart.php';

?>