<?php
session_start();
?>

<?php
//ヘッダーform表示フラグ
$form_display = 'thanks';
//ヘッダー部品
require_once '../include/template/header.php';
//定数
require_once '../include/config/const.php';
//SESSION系関数
require_once '../include/model/session.php';
//DB接続
require_once '../include/model/model.php';
//SQL関数
require_once '../include/model/sql.php';
//汎用系関数
require_once '../include/model/common.php';
?>

<?php
##DELETEに関してはログアウト時にテーブル削除が現状丸い
  // var_dump($_SESSION);
  // print'<br>';
  index_redirect($_SESSION['login_id']);

  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    $thanks_disp = 'SELECT *FROM ec_product 
    INNER JOIN ec_cart
    ON ec_product.product_id = ec_cart.product_id
    INNER JOIN ec_stock
    ON ec_product.product_id = ec_stock.product_id
    WHERE ec_product.product_id >= 0 
    AND ec_cart.user_id = ?';

    $stmt = $dbh->prepare($thanks_disp);
    $stmt->bindValue(1,$_SESSION['login_id']);
    $stmt->execute();

  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }

  foreach($stmt as $key => $row) {
    // var_dump($row);
    // print '<br>';
    $product_name[] = $row['product_name'];
    $product_image[] = $row['product_image'];
    $price[] = $row['price'];
    $product_qty[] = $row['product_qty'];
    $detect[] = $key;//新配列識別数
  }

##デバック
  print '<br>';
  var_dump($product_name);
  print '<br>';
  var_dump($product_image);
  print '<br>';
  var_dump($price);
  print '<br>';
  var_dump($product_qty);
  print '<br>';
  var_dump($detect);
  print '<br>';

##小計
foreach($detect as $val) {
  $cal[] = $price[$val] * $product_qty[$val];
}
##配列の合計金額を取得
$sum = array_sum($cal);
// var_dump($sum);
// print '<br>';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <link rel="stylesheet" href="../include/css/common.css">
  <link rel="stylesheet" href="../include/css/thanks.css">
  <title>購入完了</title>
</head>
<body>
  <?php echo "<p class=thanks>".$_GET['thanks']."</p>"?>
    <?php foreach($detect as $row):?>
      <div class="wrapper">
        <div class="left">
          <?php echo '<img src="images/'.$product_image[$row].'">';?>
          <?php echo $product_name[$row];?>
        </div>
        <div class="right">
          <div>
            <?php echo '価格:&nbsp¥&nbsp'.$price[$row]?>
          </div>
          <div>
            <?php echo '数量&nbsp:&nbsp'.$product_qty[$row].''?>
          </div>
        </div>
      </div>
    <?php endforeach;?>
    <footer>
      <p class="sum">小計&nbsp:&nbsp<?php echo $sum?>円</p>
    </footer>
</body>
</html>