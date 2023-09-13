<?php
// session_start();//ない場合:値NULL：戻ればSESSIONに値格納
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
$post = h($_POST);
var_dump($post);
print "<br>";

#POST値代入
//product_id = product & stock
$pro_name = $post['pro_name'];//product
$price = $post['price'];//product
$stock_qty = $post['stock_qty'];//stock
$public_flg = $post['public_flg'];//product & image
//$_FILES値変数代入
$image = $_FILES['image']; //product & image
$img_name = $image['name']; 

var_dump($image);
print '<br>';
var_dump($image['tmp_name']);
print '<br>';
var_dump($image['name']);
print '<br>';
var_dump($image['size']);
print '<br>';

##バリデーション
$err = [];
$str_preg = '/^[0-9]+$/';

##POSTが一つでも値が無ければ(flg以外)
if(empty($pro_name) || empty($price) || empty($stock_qty)) {
  echo"仮止め(empty精査)<br>";
  var_dump($post);
  $err[] = "全ての入力欄を記入して頂かないとデータの登録は出来ません";
  header('Location:../../ec_site/product_con.php?err_register='.$err[0].'');
  exit();
}

if(!preg_match($str_preg,$price) || !preg_match($str_preg,$stock_qty)){
  echo"仮止め(小数点正の整数精査)<br>";
  $err[] = "「値段」「在庫数」は正の整数を入力してください";
  header('Location:../../ec_site/product_con.php?err_register='.$err[0].'');
  exit();
} else{
##キャスト変換
  $price = (int)$price;
  $stock_qty = (int)$stock_qty;
  var_dump($stock_qty);
  print '<br>';
}

if($image['size'] === 0) {
  echo "仮止め(画像が選択されていない)<br>";
  $err[] = "画像を選択してください";
  header('Location:../../ec_site/product_con.php?err_register='.$err[0].'');
  exit();
} else {
  $ext = strtolower(pathinfo($image['name'],PATHINFO_EXTENSION));
  $rest = array('jpeg','jpg','png');//後でjpg消す（要件には無い）
  var_dump($ext);
  print '<br>';
##このタイミングでmove_up_load発動
  move_uploaded_file($image['tmp_name'],'../../ec_site/images/'.$image['name']);


  if(!in_array($ext,$rest)) {
    echo "仮止め(拡張子)<br>";
    $err[] = "投稿できる画像の拡張子は「jpeg」か「png」のみです";
    header('Location:../../ec_site/product_con.php?err_register='.$err[0].'');
    exit();
  }
} 

##INSERT(出来れば関数にしたい)テーブルごとSQL分ければ行けそう
if(count($err) === 0) {
  try {
##日付
    $date = today();
##product_id(オートインクリメント)
    $a_i =id_auto();
    var_dump($a_i);
    print '<br>';

    $dbh = dbConnect();
    $dbh->beginTransaction();

##product
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

##stock

    $stock = 'INSERT INTO ec_stock
    (product_id,stock_qty,create_date,update_date)
    VALUES(?,?,?,?)';

    $stmt = $dbh->prepare($stock);
    $stmt->bindValue(1,$a_i,PDO::PARAM_INT);
    $stmt->bindValue(2,$stock_qty,PDO::PARAM_INT);
    $stmt->bindValue(3,$date);
    $stmt->bindValue(4,$date);

    $stmt->execute();

##image

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

    echo $pro_name."を追加しました<br>";
##追加メッセージ
    $result =  $pro_name.'を追加しました';
    header('Location:../../ec_site/product_con.php?register='.$result.'');
    exit();

  } catch (PDOException $e) {
    $dbh->rollBack();
    echo $e->getMessage();
    exit();
  }
} else {
  var_dump($err);
  print '<br>';
##安全対策
  // header('Location:../../ec_site/product_con.php?massage='.$err[0].'');
  // exit();
}

?>