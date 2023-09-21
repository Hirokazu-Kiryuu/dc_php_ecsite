<!-- 表示ロジック最低限ファイル分けVer -->
<?php
##別ファイル読み込みテスト実装
  $array = product_con_disp();

  foreach($array as $key => $row) {
    var_dump($row);
    print '<br>';
##生成配列の長さを取得
    $arr_sum[] = $key;
##stmt値代入
    $product_id[] = $row['product_id'];
    $product_image[] = $row['product_image'];
    $product_name[] = $row['product_name'];
    $price[] = $row['price'];
    $stock_qty[] = $row['stock_qty'];
    $public_flg[] = $row['public_flg'];
    var_dump($product_id);
    print '<br>';
  }

  print '<br>';
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
  <title>管理者用ページ</title>
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/product_con.css">
</head>
<body>
  <h1>商品登録</h1>
    <form action="../include/model/product_con_register.php" method="POST" enctype="multipart/form-data">
    <form action="" method="POST">
      <div class="product_info">
        <label for="pro_name">商品名:</label>
        <input type="text" name="pro_name" id="pro_name"><br>
        <label for="price">価格:</label>
        <input type="text" name="price" id="price"><br>
        <label for="stock_qty">個数:</label>
        <input type="text" name="stock_qty" id="stock_qty"><br>
        <label for="image">商品画像:</label>
        <input type="file" name="image" id="image"><br>
        <label for="public_flg">ステータス:</label>
        <select name="public_flg" id="public_flg">
          <option value="1">公開</option>
          <option value="2">非公開</option>
        </select><br>
<!-- 登録メッセージ系 -->
          <?php echo "<p class=error>".$_GET['register_err']."</p>"?>
          <?php echo "<p class=error>".$_GET['err_m']."</p>"?>
          <?php echo "<p class=success>".$_GET['register']."</p>"?>
        <input type="submit" value="商品を登録" class="register">
      </div>
    </form>

    <form action="../include/model/logout.php" method="POST">
      <input type="hidden" name="logout" value="logout">
      <input type="submit" value="ログアウト" class="logout">
    </form>
<!-- 更新メッセージ系 -->
    <?php echo "<p class=error>".$_GET['update_err']."</p>"?>
    <?php echo "<p class=success>".$_GET['update']."</p>"?>

    <table border="1">
      <tr>
        <th>商品画像</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫数</th>
        <th>公開フラグ</th>
        <th>削除</th>
      </tr>
<!-- public_flg系処理 -->
  <?php foreach($arr_sum as $row) :?>
    <?php
##hiddenに渡すpublic_flg
        $flg_num = (int)$public_flg[$row];
##public_flg > STR変換
        if($public_flg[$row] == 1) {
          $public_flg[$row] = "非表示にする";
        } else {
          $public_flg[$row] = "表示する";
        }
    ?>

      <tr>
        <form action="../include/model/product_con_edit.php" method="POST">
          <td><?php echo '<img src="images/'.$product_image[$row].'">';?></td>
          <td>
            <input type="hidden" name="product_name" value=<?php echo $product_name[$row];?>>
            <?php echo $product_name[$row];?>
          </td>
          <td><?php echo '¥&nbsp'.$price[$row];?></td>
          <td>
<!-- 押下した行のproduct_idを識別 -->
            <input type="hidden" name="product_id" value="<?php echo $product_id[$row]?>">
            <input type="text" name="stock_num" value="<?php echo $stock_qty[$row]?>" class="text_box">
            <input type="submit" name="stock_btn" value="変更する">
          </td>
          <td>
            <input type="hidden" name="flg_num" value="<?php echo $flg_num?>";>
            <input type="submit" name="flg_btn" value="<?php echo $public_flg[$row];?>">
          </td>
          <td>
            <input type="submit" name="delete" value="削除">
          </td>
        </form>
      </tr>
    <?php endforeach;?>
  </table>
</body>
</html>



