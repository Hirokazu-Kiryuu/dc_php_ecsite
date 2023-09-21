<?php
//定数
// require_once '../config/const.php';//なんかエラる
//DB
require_once 'db_connect.php';
?>

<?php
echo "sql.php<br>";
/**
 * product_idの擬似的A_Iを生成
 * @return @param int product_idの最新のint
*/
function id_auto() {
  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    $sql = 'SELECT stock_id FROM `ec_stock` 
    ORDER BY stock_id DESC LIMIT 1';

    $stmt = $dbh->prepare($sql);

    $stmt->execute();

    $dbh->commit();

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach($rec as $val) {
      $pro_id = $val + 1;
    }

    // var_dump($pro_id);
    // print'<br>';

    return $pro_id;

  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }
}

/**
 * product_conとproduct_listの表示値
 * @return @param object $stmt
*/

function product_disp() {
  try{
    $dbh = dbConnect();
    $dbh->beginTransaction();
    $sql = 'SELECT * FROM ec_product,ec_stock WHERE ec_product.product_id = ec_stock.stock_id AND ec_product.product_id >=0';
  
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    return $stmt;
  
  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }
}

/**
 * ec_stockの在庫数の取得
 * product_idを取得できていないと機能しないので注意
 * @return @param string $rec
*/
function stock_qty() {
##product_idを頻出的に使用する場合は関数外で使用も視野に入れる
  $post = h($_POST);
  $product_id = $post['product_id'];
  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();
  
    $stock_qty =  'SELECT stock_qty FROM ec_stock
    WHERE user_id = ?';
  
    $stmt = $dbh->prepare($stock_qty);
  
    $stmt->bindValue(1,$product_id);
  
    $stmt->execute();
  
    $dbh->commit();

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach($rec as $val) {
      $row = $val;
    }

    return $row;

  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }
}

/**
 * ec_cartのproduct_qtyを取得する
 * @return @param int $num
*/
function product_qty() {
  $post = h($_POST);
  $product_id = $post['product_id'];
  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    $product_qty =  'SELECT product_qty FROM ec_cart
    WHERE product_id = ?';

    $stmt = $dbh->prepare($product_qty);

    $stmt->bindValue(1,$product_id);

    $stmt->execute();
  
    $dbh->commit();
##foreachで回すとエラー起こるので、連想配列のまま取得して加工
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    return $rec;

  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }
}


?>