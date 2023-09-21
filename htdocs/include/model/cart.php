<!-- 名称cart_edit.phpに変更 -->
<?php
session_start();//必要
var_dump($_SESSION);
print '<br>';
?>

<?php
##定数
require_once '../config/const.php';
##DB接続
require_once 'db_connect.php';
##DB系関数
require_once 'sql.php';
##汎用系関数
require_once 'common.php';
?>

<?php
$post = h($_POST);
var_dump($post);
print '<br>';
##日付
$date = today();
##正規表現変数
$str_preg = '/^[0-9]+$/';
##POST > 変数代入
$login_id = $post['login_id'];
$product_id = $post['product_id'];
$product_qty = (int)$post['product_qty'];
$product_name = $post['product_name'];
$cart_id = $post['cart_id'];
$update = $post['update'];
$delete = $post['delete'];

print '<br>';
var_dump($login_id);
print '<br>';
var_dump($product_id);
print '<br>';
var_dump($product_qty);
print '<br>';
var_dump($product_name);
print '<br>';
var_dump($cart_id);
print '<br>';
var_dump($update);
print '<br>';
var_dump($delete);
print '<br>';

try {
  $dbh = dbConnect();
  $dbh->beginTransaction();
##cart_idを取得
  $select = 'SELECT * FROM ec_cart WHERE cart_id = ? AND user_id = ? AND product_id =?';
  
  $stmt = $dbh->prepare($select);
  $stmt->bindValue(1,$cart_id);
  $stmt->bindValue(2,$login_id);
  $stmt->bindValue(3,$product_id);
  $stmt->execute();

##必要値取得
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  var_dump($rec);
  print '<br>';
  // $cart_id = $rec['cart_id'];//必要ない可能性高
  // var_dump($cart_id);
  // print '<br>';

if(isset($update)) {
  echo "更新<br>";
##変更バリデーション
  if(preg_match($str_preg,$product_qty)) {
##product_qtyの更新
    $update = 'UPDATE ec_cart SET product_qty = ?
    WHERE cart_id = ? AND user_id = ?';
    $stmt = $dbh->prepare($update);
    $stmt->bindValue(1,$product_qty);
    $stmt->bindValue(2,$cart_id);
    $stmt->bindValue(3,$login_id);
    $stmt->execute();
    $dbh->commit();

##完了メッセージ
    $result =  'カート内の'.$product_name.'の数を'.$product_qty.'個に変更しました';
    // echo $result.'<br>';
    header('Location:../../ec_site/cart.php?register='.$result.'');
    exit();
  } else {
##バリデーションエラー処理
    $result =  '商品数に入力できるのは0以上の整数のみです';
    // echo $result.'<br>';
    header('Location:../../ec_site/cart.php?err_m='.$result.'');
    exit();
  }
}
  if(isset($delete)) {
    echo "削除<br>";
    // exit();//仮止め
    var_dump($cart_id);
    print '<br>';
##cart_tableから削除
    $delete = 'DELETE FROM ec_cart WHERE cart_id = ?';
    $stmt = $dbh->prepare($delete);
    $stmt->bindValue(1,$cart_id);
    $stmt->execute();

    $dbh->commit();

##更新メッセージ
    $result =  'カート内の'.$product_name.'を削除しました';
    // echo $result.'<br>';
    header('Location:../../ec_site/cart.php?register='.$result.'');
    exit();
  }

} catch (PDOException $e) {
  $dbh->rollBack();
  echo $e->getMessage();
  exit();
}
?>