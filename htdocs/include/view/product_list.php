<?php
  try {
    $dbh = dbConnect();
    $keep = 'SELECT user_id FROM ec_user WHERE user_id = ?';
    $stmt = $dbh->prepare($keep);
    $stmt->bindValue(1,$_SESSION['login_id']);
    $stmt->execute();
  
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($rec);
    print'<br>';
  
  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }

##商品一覧SELECT関数
  $array = product_disp();

##$stmt->変数代入
  foreach($array as $key => $row) {
##出力時foreach参照先配列の作成
    $arr_sum[] = $key;
    ##DB値->変数代入
    $product_id[] = $row['product_id'];
    $product_image[] = $row['product_image'];
    $product_name[] = $row['product_name'];
    $price[] = $row['price'];
    $public_flg[] = $row['public_flg'];//フラグ判別
    $stock_qty[] = $row['stock_qty'];//在庫判別
  }

##デバック
    var_dump($arr_sum);
    print '<br>';
    var_dump($product_id);
    print '<br>';
    var_dump($product_image);
    print '<br>';
    var_dump($product_name);
    print '<br>';
    var_dump($price);
    print '<br>';
    var_dump($stock_qty);
    print '<br>';
    var_dump($public_flg);
    print '<br>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/product_list.css">
  <title>商品一覧</title>
</head>
<body>
  <!-- GETメッセージ -->
<?php echo "<p class=success>".$_GET['register']."</p>"?>
  <div class="wrapper">
    <?php foreach($arr_sum as $row):?>
      <?php if($public_flg[$row] == 1):?>
        <div class="content">
          <div class="inner">
            <form action="../include/model/product_list.php" method="POST" class="list_form">
              <?php echo '<img src="images/'.$product_image[$row].'">';?>
              <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id'];?>">
              <input type="hidden" name="product_id" value="<?php echo $product_id[$row];?>">
                <div class="info">
                  <input type="hidden" name="product_name" value="<?php echo $product_name[$row]?>">
                  <p><?php echo $product_name[$row]?></p>
                  <p><?php echo $price[$row].'円'?></p>
                </div>
                  <?php 
                    if($stock_qty[$row] != 0) {
                      echo '<input type="hidden" name="pro_key" value="1">';
                      echo '<input type="submit" name="push" value="カートに入れる">';
                    } else {
                      echo '<strong>売り切れ</strong>';
                    }
                  ?>
            </form>
          </div>
        </div>
      <?php endif;?>
    <?php endforeach;?>
  </div>
</body>
</html>