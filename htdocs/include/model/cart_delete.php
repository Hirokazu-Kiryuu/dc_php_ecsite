<?php
session_start();
?>

<?php
#定数
require_once '../config/const.php';
//DB接続
require_once 'model.php';
//SQL関数
require_once 'sql.php';
//汎用系関数
require_once 'common.php';
?>

<?php
$user_id = $_SESSION['login_id'];
var_dump($user_id);
print '<br>';
##cartテーブル削除（後で復活）
// try {
//   $dbh = dbConnect();
//   $dbh->beginTransaction();

//   $delete = 'DELETE FROM ec_cart WHERE user_id = ?';

//   $stmt = $dbh->prepare($delete);

//   $stmt->bindValue(1,$user_id);

//   $stmt->execute();

//   $dbh->commit();

  header('Location:../../ec_site/product_list.php');
  exit();

// } catch (PDOException $e) {
//   $dbh->rollBack();
//   echo $e->getMessage();
//   exit();
// }

?>