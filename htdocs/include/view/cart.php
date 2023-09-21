<?php

  try{
    $dbh = dbConnect();
    $dbh->beginTransaction();

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

  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }

  foreach($stmt as $key => $row) {
    // var_dump($row);
    // print '<br>';
    $arr_sum[] = $key;
    $product_id[] = $row['product_id'];
    $product_name[] = $row['product_name'];
    $price[] = $row['price'];
    $product_image[] = $row['product_image'];
    $stock_qty[] = $row['stock_qty'];
    $cart_id[] = $row['cart_id'];
    $user_id[] = $row['user_id'];
    $product_qty[] = $row['product_qty'];
  }

##デバック
  print '<br>';
  var_dump($arr_sum);
  print '<br>';
  var_dump($product_id);
  print '<br>';
  var_dump($product_name);
  print '<br>';
  var_dump($price);
  print '<br>';
  var_dump($product_image);
  print '<br>';
  var_dump($stock_qty);
  print '<br>';
  var_dump($cart_id);
  print '<br>';
  var_dump($user_id);
  print '<br>';
  var_dump($product_qty);
  print '<br>';

##小計計算
  foreach($arr_sum as $val) {
    $cal[] = $price[$val] * $product_qty[$val];
  }
  $sum = array_sum($cal);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ショッピングカート</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
  <br><a href="../ec_site/product_list.php">一覧ページへ(仮)</a>
    <?php echo "<p class=error>".$_GET['err_m']."</p>"?>
    <?php echo "<p class=success>".$_GET['register']."</p>"?>
      <?php foreach($arr_sum as $row):?>
        <div class="wrapper">
          <div class="left">
            <?php echo '<img src="images/'.$product_image[$row].'">'?>
            <p><?php echo $product_name[$row]?></p>
          </div>
          <form action="../include/model/cart.php" method="POST">
            <div class="right">
              <input type="hidden" name="login_id" value="<?php echo $_SESSION['login_id']?>">
              <input type="hidden" name="product_id" value="<?php echo $product_id[$row]?>">
              <input type="hidden" name="cart_id" value="<?php echo $cart_id[$row]?>">
              <input type="hidden" name="product_name" value="<?php echo $product_name[$row]?>">
              <input type="submit" name="delete" value="削除">
              <p><?php echo '価格&nbsp:&nbsp'.$price[$row]?></p>
              <p>数量&nbsp:&nbsp<input type="text" name="product_qty" value="<?php echo $product_qty[$row]?>" class="text_box"></p>
              <input type="submit" name="update" value="変更する">
            </div>
          </form>
        </div>
      <?php endforeach;?>
  <footer>
    <form action="../include/model/cart_branch.php" method="POST" class="footer_form">
      <p class="sum">小計&nbsp:&nbsp<?php echo $sum;?>円</p>
        <?php foreach($arr_sum as $row):?>
<!-- name=x[]の形にしないといけないのは恐らく欠陥がある -->
          <input type="hidden" name="arr_sum[]" value="<?php echo $row?>">
          <input type="hidden" name="product_id[]" value="<?php echo $product_id[$row]?>">
          <input type="hidden" name="product_name[]" value="<?php echo $product_name[$key]?>">
          <input type="hidden" name="stock_qty[]" value="<?php echo $stock_qty[$row]?>">
          <input type="hidden" name="cart_id[]" value="<?php echo $cart_id[$row]?>">
          <input type="hidden" name="user_id[]" value="<?php echo $user_id[$row]?>">
          <input type="hidden" name="product_qty[]" value="<?php echo $product_qty[$row]?>">
        <?php endforeach;?>
        <input type="submit" name="buy_btn" value="購入する" class="common_btn">
    </form>
  </footer>
</body>
</html>