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
##POST値代入
$post = h($_POST);
var_dump($post);
print '<br>';

$pro_name = $post['pro_name'];
$price = $post['price'];
$stock_qty = $post['stock_qty'];
$public_flg = $post['public_flg'];

##FILE値代入
$image = $_FILES['image'];
$img_name = $image['name'];



##デバック
var_dump($image);
print '<br>';
// var_dump($img_name);
// print '<br>';
// var_dump($image['name']);
// print '<br>';
// var_dump($image['type']);
// print '<br>';
// var_dump($image['tmp_name']);
// print '<br>';
// var_dump($image['error']);
// print '<br>';
// var_dump($image['size']);
// print '<br>';

##入力バリデーション
$err = [];
$str_preg = '/^[0-9]+$/';

##入力確認
if(empty($pro_name) || empty($price) || empty($stock_qty)) {
  // echo "仮止め入力チェック<br>";
  $err[] = "全ての入力欄を記入して頂かないとデータの登録が出来ません";
  header('Location:../../ec_site/product_con.php?register_err='.$err[0].'');
  exit();
}

##正規表現チェック
if(!preg_match($str_preg,$price) || !preg_match($str_preg,$stock_qty)){
  // echo"仮止め(小数点正の整数精査)<br>";
  $err[] = "「値段」「在庫数」は正の整数を入力してください";
  header('Location:../../ec_site/product_con.php?register_err='.$err[0].'');
  exit();
} else{
##キャスト変換
  $price = (int)$price;
  $stock_qty = (int)$stock_qty;
  // var_dump($stock_qty);
  // print '<br>';
}

##画像バリデーション
if($image['size'] === 0) {
  // echo "仮止め画像が選択されていない<br>";
  $err[] = "画像を選択してください";
  header('Location:../../ec_site/product_con.php?register_err='.$err[0].'');
  exit();
} else {
##拡張子変換
  $ext = strtolower(pathinfo($image['name'],PATHINFO_EXTENSION));
  $rest = array('jpeg','jpg','png');
##move_up_load
  move_uploaded_file($image['tmp_name'],'../../ec_site/images/'.$img_name);
##拡張子バリデーション
  if(!in_array($ext,$rest)) {
    // echo "仮止め拡張子<br>";
    $err[] = "投稿できる画像は「jpeg」か「jpg」か「png」のみです";
    header('Location:../../ec_site/product_con.php?register_err='.$err[0].'');
    exit();
  }
}


var_dump($err);
print '<br>';

##INSERT
if(count($err) === 0) {
##日付関数
  $date = today();
##product_idオートインクリメント関数
  $a_i = id_auto();
  var_dump($a_i);
  print '<br>';
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();

##ec_product
      $product = 'INSERT INTO ec_product
      (product_name,price,product_image,public_flg,create_date,update_date)
      VALUES(?,?,?,?,?,?)';

      $stmt = $dbh->prepare($product);

      $stmt->bindValue(1,$pro_name,PDO::PARAM_STR);
      $stmt->bindValue(2,$price,PDO::PARAM_INT);
      $stmt->bindValue(3,$img_name,PDO::PARAM_STR);
      $stmt->bindValue(4,$public_flg,PDO::PARAM_INT);
      $stmt->bindValue(5,$date);
      $stmt->bindValue(6,$date);

      $stmt->execute();

##ec_stock
    $stock = 'INSERT INTO ec_stock
    (product_id,stock_qty,create_date,update_date)
    VALUES(?,?,?,?)';

    $stmt = $dbh->prepare($stock);
    $stmt->bindValue(1,$a_i,PDO::PARAM_INT);
    $stmt->bindValue(2,$stock_qty,PDO::PARAM_INT);
    $stmt->bindValue(3,$date);
    $stmt->bindValue(4,$date);

    $stmt->execute();

##ec_image
    $image = 'INSERT INTO ec_image
    (image_name,public_flg,create_date,update_date)
    VALUES(?,?,?,?)';

    $stmt = $dbh->prepare($image);

    $stmt->bindValue(1,$img_name,PDO::PARAM_STR);
    $stmt->bindValue(2,$public_flg,PDO::PARAM_INT);
    $stmt->bindValue(3,$date);
    $stmt->bindValue(4,$date);

    $stmt->execute();

    $dbh->commit();

##追加メッセージ
    // echo $pro_name."を追加しました";
    $result = $pro_name."を追加しました";
    header('Location:../../ec_site/product_con.php?register='.$result.'');
    exit();

  } catch(PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }
}

?>

