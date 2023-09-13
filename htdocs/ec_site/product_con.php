<?php
session_start();
?>

<?php
//定数
require_once '../include/config/const.php';
//SESSION系関数関数
require_once '../include/model/session.php';
//DB接続
require_once '../include/model/model.php';
//SQL関数
require_once '../include/model/sql.php';
//汎用系関数
require_once '../include/model/common.php';
?>

<?php
var_dump($_SESSION['login_id']);
print'<br>';

index_redirect($_SESSION['login_id']);
##追加機能(管理者用のログインid以外はユーザーページにリダイレクトする)
list_redirect($_SESSION['login_id']);

##商品ディレクトリ用SELECT文
$stmt = product_disp();

##$stmt->変数代入
foreach($stmt as $row) {
##出力時foreach参照先配列の作成
  $product_id[] = $row['product_id'];//(foreach用)
  $num = count($product_id);
  $cal[] = $num -1;//出力foreach参照変数
  $id[] = $row[0];//product_id識別子
##DB値->変数代入
  $product_image[] = $row['product_image'];
  $product_name[] = $row['product_name'];
  $price[] = $row['price'];
  $stock_qty[] = $row['stock_qty'];
  $public_flg[] = $row['public_flg'];
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
print '<br>';
var_dump($public_flg);
print '<br>';
// var_dump($cal);
// print '<br>';
var_dump($id);
print '<br>';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../include/css/common.css">
  <link rel="stylesheet" href="../include/css/product_con.css">
  <title>商品管理</title>
</head>
<body>
  <h1>商品登録</h1>
  <form action="../include/model/con_register.php" method="POST" enctype="multipart/form-data">
    <div class="product_info">
      <label for="pro_name">商品名:</label>
      <input type="text" name="pro_name" id="pro_name"><br/>
      <label for="price">価格:</label>
      <input type="text" name="price" id="price"><br/>
      <label for="stock_qty">個数:</label>
      <input type="text" name="stock_qty" id="stock_qty"><br/>
      <label for="image">商品画像:</label>
      <input type="file" name="image" id="image"><br/>
      <label for="public_flg">ステータス:</label>
      <select name="public_flg" id="public_flg">
        <option value="1">公開</option>
        <option value="2">非公開</option>
      </select><br/>
<!-- 登録メッセージ -->
      <?php echo "<p class=error>".$_GET['err_register']."</p>"?>
      <?php echo "<p class=error>".$_GET['err_m']."</p>"?>
      <?php echo "<p class=success>".$_GET['register']."</p>"?>
      <input type="submit" class="register" value="商品を登録"><br>
    </div>
  </form>
  <form action="../include/model/logout.php" method="POST">
    <input type="hidden" name="logout" value="logout">
    <input type="submit" class="logout" value="ログアウト">
  </form>
<!-- 一覧メッセージ -->
<?php echo "<p class=error>".$_GET['err_update']."</p>"?>
<?php echo "<p class=success>".$_GET['update']."</p>"?>

<!-- 商品が追加されるとテーブルが生成 -->
<table border ="1">
  <tr>
    <th>商品画像</th>
    <th>商品名</th>
    <th>価格</th>
    <th>在庫数</th>
    <th>公開フラグ</th>
    <th>削除</th>
  </tr>

<?php foreach($cal as $row):?>
  <?php 
##hiddenに渡すpublic_flg(num)
    $flg_num = (int)$public_flg[$row];
##pub_flg > STR変換
    if($public_flg[$row] == 1) {
      $public_flg[$row] = "非表示にする";
    } 
    else {
      $public_flg[$row] = "表示する";
    }
  ?>
  <tr>
  <form method="post" action="../include/model/con_edit.php">
    <td><?php echo '<img src="images/'.$product_image[$row].'">';?></td>
    <td>
      <input type="hidden" name="product_name" value="<?php echo $product_name[$row]?>">
      <?php echo $product_name[$row];?>
    </td>
    <td><?php echo '¥&nbsp'.$price[$row];?></td>
    <td>

<!-- 押下した行のproduct_idを識別(後でhidden) -->
        <input type="hidden" name="product_id" value="<?php echo $id[$row];?>">
        <input type="text" class="text_box" name="stock_num" value="<?php echo $stock_qty[$row];?>">
        <input type="submit" name="stock_btn" value="変更する">
      </td>
        <td>
          <input type="hidden" name="flg_num" value="<?php echo $flg_num?>">
          <input type="submit" name="flg_btn" value="<?php echo $public_flg[$row];?>">
        </td>
        <td>
          <input type="submit" name="delete" value="削除する">
        </td>
      </form>
    </td>
  </tr>
<?php endforeach;?>
</body>
</html>