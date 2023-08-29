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

// } catch (PDOException $e) {
//   $dbh->rollBack();
//   echo $e->getMessage();
//   exit();
// }



##ログアウトメッセージ代入
$logout_m = LOGOUT_M;

##ログアウト機能実装
if(isset($_POST['logout'])) {
//Cookie上のセッション名を取得代入
  $session = session_name();
//セッションの値をからにする
  unset($_SESSION['login_id']);
#Cookie上のセッションを削除
  if(isset($_COOKIE[$session])) {
//セッションの情報（現状必要ない説濃厚）
    $params = session_get_cookie_params();
    setcookie($session,'',time() -30, '/');
    header('Location:../../ec_site/index.php?massage='.$logout_m.'');
    exit();
  }
  elseif(empty($_SESSION['login_id'])) {
    $err_m = "ログインされていません";
    header('Location:../../ec_site/index.php?massage='.$err_m.'');
  }
}

##デバックエリア
var_dump($_POST);
print'<br>';
var_dump($_SESSION);
print'<br>';

?>