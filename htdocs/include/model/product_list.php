<?php
session_start();
var_dump($_SESSION);
print '<br>';
?>

<?php
##定数
require_once '../config/const.php';
##DB接続
require_once 'db_connect.php';
##汎用系関数
require_once 'common.php';
##DB系関数
require_once 'sql.php';
?>

<?php
  $post = h($_POST);
  var_dump($post);
  print "<br>";
  #日付
  $date = today();

##post値代入
  $user_id = $post['user_id'];
  $product_id = $post['product_id'];
  $pro_key = $post['pro_key'];//cartの中に入れる値
  $product_name = $post['product_name'];
  $push = $post['push'];

  if(isset($push)) {
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();
##user_idを基準にecテーブルを取得する
      $select = 'SELECT * FROM ec_cart WHERE user_id = ? AND product_id =?';
      $stmt = $dbh->prepare($select);
      $stmt->bindValue(1,$user_id);
      $stmt->bindValue(2,$product_id);
      $stmt->execute();
##$select要素の配列化
      foreach($stmt as $row) {
        var_dump($row);
        print '<br>';
        $cart_id = (int)$row['cart_id'];
        $db_product_id = $row['product_id'];
        $product_qty = (int)$row['product_qty'];
      }
##デバック
        var_dump($cart_id);
        print '<br>';
        var_dump($db_product_id);
        print '<br>';
        var_dump($product_qty);
        print '<br>';
        var_dump($user_id);
        print '<br>';


##user_idのユーザーがDBに商品のproduct_idを持っているか？
      if(!empty($db_product_id)) {
##product_qtyのインクリメント処理
        $product_qty += 1;
        var_dump($product_qty);
        print '<br>';
##++処理をproduct_idとuser_idを参照して更新
        $update = 'UPDATE ec_cart SET product_qty = ? WHERE user_id = ? AND product_id =? ';
        $stmt = $dbh->prepare($update);
        $stmt->bindValue(1,$product_qty);
        $stmt->bindValue(2,$user_id);
        $stmt->bindValue(3,$db_product_id);
        $stmt->execute();
        $dbh->commit();

        $result =  $product_name.'を1個カートに追加しました。現在'.$product_name.'は'.$product_qty.'個カートに入っています';
        // echo $result.'<br>';
        header('Location:../../ec_site/product_list.php?register='.$result.'');
        exit();
      }

##ec_cart table INSERT構文
    $ec_cart = 'INSERT INTO ec_cart
    (user_id,product_id,product_qty,create_date,update_date)
    VALUES(?,?,?,?,?)';

    $stmt = $dbh->prepare($ec_cart);

    $stmt->bindValue(1,$user_id);
    $stmt->bindValue(2,$product_id);
    $stmt->bindValue(3,$pro_key);
    $stmt->bindValue(4,$date);
    $stmt->bindValue(5,$date);

    $stmt->execute();

    $dbh->commit();

    $result =  $product_name.'をカートに追加しました';
    // echo $result.'<br>';
    header('Location:../../ec_site/product_list.php?register='.$result.'');
    exit();

    } catch(PDOException $e) {
      $dbh->rollBack();
      echo $e->getMessage();
      exit();
    }
  }

?>