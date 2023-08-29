<!-- 未 -->

<?php
//デバックコード
session_start();
?>

<?php
//ヘッダーform表示フラグ
$form_display = TRUE;
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
  // PHPエラーを非表示（完成後復活）
  error_reporting(0);

  var_dump($_SESSION);
  print'<br>';
  index_redirect($_SESSION['login_id']);

  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    // $cart_disp = 'SELECT *FROM ec_product 
    // INNER JOIN ec_cart
    // ON ec_product.product_id = ec_cart.product_id
    // WHERE ec_product.product_id >= 0 
    // AND ec_cart.user_id = ?';

//thanksと同じSQL文なので関数化はあり
    $cart_disp = 'SELECT *FROM ec_product 
    INNER JOIN ec_cart
    ON ec_product.product_id = ec_cart.product_id
    INNER JOIN ec_stock
    ON ec_product.product_id = ec_stock.product_id
    WHERE ec_product.product_id >= 0 
    AND ec_cart.user_id = ?';
  
    $stmt = $dbh->prepare($cart_disp);
    $stmt->bindValue(1,$_SESSION['login_id']);
    $stmt->execute();

  } catch (PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }

  foreach($stmt as $key => $row) {
    $product_id[] = $row['product_id'];
    $id[] = $row[0];//product_id識別子（product_idで良い（使ってない））
    $product_name[] = $row['product_name'];
    $price[] = $row['price'];
    $product_image[] = $row['product_image'];
    $stock_qty[] = $row['stock_qty'];
    $cart_id[] = $row['cart_id'];
    $user_id[] = $row['user_id'];
    $product_qty[] = $row['product_qty'];
    $detect[] = $key;//新配列識別数
    // $detect[] = $key;//新配列識別数
    // $num = count($product_id);
    // $cal[] = $num -1;//旧配列識別数
  }

##デバック
  // var_dump($product_id);
  // print '<br>';
  // var_dump($id);
  // print '<br>';
  // var_dump($product_name);
  // print '<br>';
  // var_dump($price);
  // print '<br>';
  // var_dump($product_image);
  // print '<br>';
  // var_dump($stock_qty);
  // print '<br>';
  // var_dump($cart_id);
  // print '<br>';
  // var_dump($user_id);
  // print '<br>';
  // var_dump($product_qty);
  // print '<br>';
  // var_dump($detect);
  // print '<br>';
  // var_dump($cal);
  // print '<br>';


##小計
##array_sum用計算配列作成（関数作成済み）
  foreach($detect as $val) {
    $cal[] = $price[$val] * $product_qty[$val];
  }
##配列の合計金額を取得
  $sum = array_sum($cal);
  // var_dump($sum);
  // print '<br>';
?>

<?php 
  // foreach($detect as $row) {
  // var_dump($row);
  // print '<br>';
  // }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <link rel="stylesheet" href="../include/css/common.css">
  <link rel="stylesheet" href="../include/css/cart.css">
  <title>ショッピングカート</title>
</head>
<body>
  <br><a href="product_list.php">一覧ページへ戻る</a><br>
  <?php echo "<p class=error>".$_GET['err_m']."</p>"?>
  <?php echo "<p class=success>".$_GET['register']."</p>"?>
    <?php foreach($detect as $row):?>
      <div class="wrapper">
        <div class="left">
          <?php echo '<img src="images/'.$product_image[$row].'">'?>
          <p><?php echo $product_name[$row]?></p>
        </div>
        <form method="post" action="../include/model/cart_edit.php">
          <div class="right">
            <input type="hidden" name="login_id" value="<?php echo $_SESSION['login_id']?>" >
            <input type="hidden" name="product_id" value="<?php echo $product_id[$row]?>">
            <input type="hidden" name="cart_id" value="<?php echo $cart_id[$row]?>">
            <input type="hidden" name="product_name" value="<?php echo $product_name[$row]?>">
            <input type="submit" name="delete" value="削除">
            <p><?php echo '価格:&nbsp¥&nbsp'.$price[$row]?></p>
            <p>数量&nbsp:&nbsp<input type="text" class="text_box" name="product_qty" value=<?php echo $product_qty[$row]?>></p>
            <input type="submit" name="update" value="変更する">
          </div>
        </form>
      </div>
    <?php endforeach;?>
  <footer>
<!-- action=thanks_branch.phpに飛ばす -->
    <form method="post" action="../include/model/thanks_branch.php" class="footer_form">
      <p class="sum">小計&nbsp:&nbsp<?php echo $sum;?>円</p>
<!-- name=x[]で配列送る構文 -->
        <?php foreach($detect as $key => $row):?>
          <input type="hidden" name="detect[]" value="<?php echo $key?>">
          <input type="hidden" name="product_id[]" value="<?php echo $product_id[$row];?>">
          <input type="hidden" name="product_name[]" value="<?php echo $product_name[$row];?>">
          <input type="hidden" name="stock_qty[]" value="<?php echo ($stock_qty[$row]);?>">
          <input type="hidden" name="cart_id[]" value="<?php echo ($cart_id[$row]);?>">
          <input type="hidden" name="user_id[]" value="<?php echo ($user_id[$row]);?>">
          <input type="hidden" name="product_qty[]" value="<?php echo ($product_qty[$row]);?>">
        <?php endforeach;?>
      <input type="submit" name="buy_btn" value="購入する" class="common_btn">
    </form>
  </footer>
</body>
</html>