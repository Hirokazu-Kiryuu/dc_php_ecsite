<!-- thanks_branch.phpの代替 -->
<?php
  session_start();
  var_dump($_SESSION['login_id']);
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
  // $post = $_POST;
  // var_dump($post);
  // print '<br>';
##POST変数代入
  $arr_sum = $_POST['arr_sum'];//配列識別子番号（予備）
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $stock_qty = $_POST['stock_qty'];
  $cart_id = $_POST['cart_id'];//DELETE用
  $user_id = $_POST['user_id'];//いちよ
  $product_qty = $_POST['product_qty'];
##デバック
  var_dump($arr_sum);
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

##SELECT(在庫判別)
      foreach($arr_sum as $row) {
        // $id[] = $product_id[$row];// 旧コード配列化に意義があるなら代替できる

        $select = 'SELECT * FROM ec_product,ec_stock,ec_cart
        WHERE ec_cart.product_id = ec_stock.product_id 
        AND ec_product.product_id = ? AND ec_cart.cart_id = ? AND ec_cart.user_id = ?';

        $stmt = $dbh->prepare($select);

        $stmt->bindValue(1,$product_id[$row]);
        $stmt->bindValue(2,$cart_id[$row]);
        $stmt->bindValue(3,$user_id[$row]);

        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        // var_dump($rec);
        // print '<br>';

        // var_dump($rec['stock_qty']);
        // print '<br>';
        // var_dump($rec['product_qty']);
        // print '<br>';

 ##在庫（計算式）
      $cal = $rec['stock_qty'] - $rec['product_qty'];
      var_dump($cal);
      print '<br>';
##在庫切れバリデーション
      if($cal < 0) {
##在庫切れの商品名配列格納
        $sold_name[] = $rec['product_name'];
        var_dump($sold_name);
        print '<br>';
      } elseif(empty($sold_name)) {
##UPDATEフラグ
          echo "アップデートフラグ<br>";
          $stock_flg = TRUE;
          var_dump($stock_flg);
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
    throw new PDOException('申し訳ございません。'.$sold_out.'の在庫がなくなりました');
  } 

  if($stock_flg === TRUE) {
    // echo "アップデート構文<br>";
    foreach($stock_qty as $key => $val) {
      $result[] = $val - $product_qty[$key];

##UPDATE文
      $update = 'UPDATE ec_stock SET stock_qty = ? WHERE product_id = ?';

      $stmt = $dbh->prepare($update);
      $stmt->bindValue(1,$result[$key]);
      $stmt->bindValue(2,$product_id[$key]);
      $stmt->execute();
    }
  }

  $dbh->commit();
##購入完了メッセージ
  $result = '購入が完了しました、ありがとうございました!';
  // echo $result.'<br>';
  header('Location:../../ec_site/thanks.php?thanks='.$result.'');
  exit();


##在庫切れ時の例外処理
    } catch (PDOException $e) {
      $dbh->rollBack();
      echo $e->getMessage();
      header('Location:../../ec_site/cart.php?err_m='.$e->getMessage().'');
      exit();
##汎用例外処理
    } catch (PDOException $e) {
      $dbh->rollBack();
      echo $e->getMessage();
      exit();
    }
  }

?>