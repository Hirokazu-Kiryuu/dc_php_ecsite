<?php
session_start();

//SESSION系関数
require_once '../include/model/session.php';
var_dump($_SESSION);
print'<br>';
index_redirect($_SESSION['login_id']);
?>

<?php
$form_display = 'thanks';
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
require_once '../include/view/thanks.php';
?>

