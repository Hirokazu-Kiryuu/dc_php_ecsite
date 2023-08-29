<?php
session_start();//必要かも
?>

<?php
//定数
require_once '../config/const.php';
//SESSION系関数関数
require_once 'session.php';
//DB接続
require_once 'model.php';
//SQL関数
require_once 'sql.php';
//汎用系関数
require_once 'common.php';
?>

<?php
var_dump($_SESSION);
print '<br>';

// $post = h($_POST);//hash関数引っかかるので、今回は無し

// var_dump($_POST);
// print '<br>';
$detect = $_POST['detect'];//配列識別子番号（予備）
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$stock_qty = $_POST['stock_qty'];
$cart_id = $_POST['cart_id'];//DELETE用
$user_id = $_POST['user_id'];//いちよ
$product_qty = $_POST['product_qty'];
var_dump($detect);
print '<br>';
var_dump($product_id);
print '<br>';
var_dump($product_name);
print '<br>';
var_dump($stock_qty);
print '<br>';
var_dump($product_qty);
print '<br>';
var_dump($cart_id);
print '<br>';
var_dump($user_id);
print '<br>';

##購入処理
if(isset($_POST['buy_btn'])) {
  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    ##SELECT構文(在庫判別)
    foreach($detect as $key => $row) {
      $id[] = $product_id[$key];
      
      $select = 'SELECT * FROM ec_product,ec_stock,ec_cart
      WHERE ec_cart.product_id = ec_stock.product_id 
      AND ec_product.product_id = ? AND ec_cart.cart_id = ? AND ec_cart.user_id = ?';
  
      $stmt = $dbh->prepare($select);
      
      $stmt->bindValue(1,$id[$key]);
      $stmt->bindValue(2,$cart_id[$key]);
      $stmt->bindValue(3,$user_id[$key]);
  
      $stmt->execute();
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);

      var_dump($rec['stock_qty']);
      print '<br>';

##在庫切れ精査
    if($rec['stock_qty'] <= 0) {
        $sold_name[] = $rec['product_name'];
        var_dump($sold_name);
        print '<br>';
      }
    }

##在庫切れ例外処理
    if(isset($sold_name)) {
##在庫切れの商品名を配列から取り出す
      $sold_out = implode("と",$sold_name);
      var_dump($sold_out);
      print '<br>';
##例外処理スロー
      throw new Exception('申し訳ございません。'.$sold_out.'の在庫がなくなりました');
    }

    foreach($stock_qty as $key => $val) {
##$result[] = 在庫数($val) - 購入数($pro_qty[$key])
      $result[] = $val - $product_qty[$key];//keyで番号取得
      var_dump($result[$key]);
      print '<br>';
      $id[] = $product_id[$key];
##UPDATE文
      $update = 'UPDATE ec_stock SET stock_qty = ? WHERE product_id = ?';

      $stmt = $dbh->prepare($update);
      
      $stmt->bindValue(1,$result[$key]);
      $stmt->bindValue(2,$id[$key]);

      $stmt->execute();
  }

      $dbh->commit();
      $result = '購入が完了しました、ありがとうございました!';
      echo $result.'<br>';
      header('Location:../../ec_site/thanks.php?thanks='.$result.'');
      exit();

##在庫切れの例外処理
  } catch (Exception $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    header('Location:../../ec_site/cart.php?err_m='.$e->getMessage().'');
    exit();

##汎用的な例外処理
  } catch (PDOException $e) {
  $dbh->rollBack();
  echo $e->getMessage();
  exit();
  } 
}

?>