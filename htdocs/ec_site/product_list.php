<?php
##SESSIONの値を持たせる
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

try {
  $dbh = dbConnect();
  $keep = 'SELECT user_id FROM ec_user WHERE user_id = ?';
  $stmt = $dbh->prepare($keep);
  $stmt->bindValue(1,$_SESSION['login_id']);
  $stmt->execute();

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($rec);
  // print'<br>';

} catch(PDOException $e) {
  $dbh->rollBack();
  echo $e->getMessage();
  exit();
}

var_dump($_SESSION);
print'<br>';

#ログインページへのリダイレクト
index_redirect($_SESSION['login_id']);

##商品ディレクトリ用SELECT関数
$rec = product_disp();

##$stmt->変数代入
foreach($rec as $row) {
##出力時foreach参照先配列の作成
    $product_id[] = $row['product_id'];//(foreach用)
    $num = count($product_id);
    $cal[] = $num -1;//配列識別数
    $id[] = $row[0];//product_id識別子
##DB値->変数代入
    $product_image[] = $row['product_image'];
    $product_name[] = $row['product_name'];
    $price[] = $row['price'];
    $public_flg[] = $row['public_flg'];//フラグ判別
    $stock_qty[] = $row['stock_qty'];//在庫判別
  }

##デバック

  var_dump($product_id);
  print '<br>';
  var_dump($product_image);
  print '<br>';
  var_dump($product_name);
  print '<br>';
  var_dump($price);
  print '<br>';
  var_dump($stock_qty);
  print '<br>pub_flg<br>';
  var_dump($public_flg);
  print '<br>';
  var_dump($cal);
  print '<br>';
  var_dump($id);
  print '<br>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/header.css">
  <link rel="stylesheet" href="../include/css/common.css">
  <link rel="stylesheet" href="../include/css/product_list.css">
  <title>商品一覧</title>
</head>
<body>
  <?php echo "<p class=success>".$_GET['register']."</p>"?>
<!-- cart_idを取得してPOSTする -->
<div class="wrapper">
  <?php foreach($cal as $row):?>
    <?php if($public_flg[$row] == 1):?>
      <div class=content>
        <div class="inner">
          <form method="post" action="../include/model/list_register.php" class=list_form>
            <?php echo '<img src="images/'.$product_image[$row].'">'?>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id'];?>">
            <input type="hidden" name="product_id" value="<?php echo $id[$row];?>">

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