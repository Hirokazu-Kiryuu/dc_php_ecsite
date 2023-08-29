<?php
session_start();
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

##POST値ハッシュ
  $post = h($_POST);
  var_dump($post);
  print '<br>';
##product_idで一意に識別
  $product_id = $post['product_id'];
##product_name
  $product_name = $post['product_name'];
##正規表現変数
  $str_preg = '/^[0-9]+$/';

##在庫数変更
  $stock_btn = $post['stock_btn'];
  $stock_num = $post['stock_num'];
  var_dump($stock_num);
  print '<br>';
  if(isset($stock_btn)) {
    if(preg_match($str_preg,$stock_num)) {
      try{
        $dbh = dbConnect();
        $dbh->beginTransaction();
        $stock_update = 'UPDATE ec_stock SET stock_qty=? WHERE product_id=?';
        $stmt = $dbh->prepare($stock_update);

        $stmt->bindValue(1,$stock_num,PDO::PARAM_INT);
        $stmt->bindValue(2,$product_id,PDO::PARAM_INT);
    
        $stmt->execute();
    
        $dbh->commit();

##変更メッセージ
        $result =  $product_name.'の在庫数を'.$stock_num.'個に変更しました';
        header('Location:../../ec_site/product_con.php?update='.$result.'');
        exit();
      } catch (PDOException $e) {
        $dbh->rollBack();
        echo $e->getMessage();
        exit();
      }

    } else {
      $err = "在庫数に入力できるのは0以上の整数のみです";
      header('Location:../../ec_site/product_con.php?err_m='.$err.'');
      exit();
    }
  }

##公開非公開変更
$flg_btn = $post['flg_btn'];
$flg_num = $post['flg_num'];

  if(isset($flg_btn)) {
    echo $flg_num.'=flg_num<br>';
  if($flg_num == 1) {
      $flg_num = 2;
      $flg_massage = "非公開";
    } elseif($flg_num == 2) {
      $flg_num = 1;
      $flg_massage = "公開";
    }

    try{
      $dbh = dbConnect();
      $dbh->beginTransaction();
      $flg_update = 'UPDATE ec_product JOIN ec_image 
      ON ec_product.public_flg = ec_image.public_flg
      SET ec_product.public_flg =?,ec_image.public_flg = ?
      WHERE product_id = ?';
      $stmt = $dbh->prepare($flg_update);

      $stmt->bindValue(1,$flg_num,PDO::PARAM_INT);
      $stmt->bindValue(2,$flg_num,PDO::PARAM_INT);
      $stmt->bindValue(3,$product_id,PDO::PARAM_INT);

      $stmt->execute();

      $dbh->commit();

      echo $flg_num.'<br>';
      $result = $product_name.'を'.$flg_massage.'に変更しました';
      header('Location:../../ec_site/product_con.php?update='.$result.'');
      exit();

    } catch (PDOException $e) {
      $dbh->rollBack();
      echo $e->getMessage();
      exit();
    }
  }

##削除
$delete = $post['delete'];
  if(isset($delete)) {
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();
##ec_product削除
      $product_delete = 'DELETE FROM ec_product WHERE product_id = ?';

      $stmt = $dbh->prepare($product_delete);
      $stmt->bindValue(1,$product_id,PDO::PARAM_INT);
      $stmt->execute();

      ##ec_stock_NULLに更新
      $stock_change = 'UPDATE ec_stock 
      SET product_id = ?,stock_qty = ? WHERE product_id = ?';

      $stmt = $dbh->prepare($stock_change);
      $stmt->bindValue(1,NULL);
      $stmt->bindValue(2,NULL);
      $stmt->bindValue(3,$product_id,PDO::PARAM_INT);
      $stmt->execute();

##ec_image削除
      $image_delete = 'DELETE FROM ec_image WHERE image_id = ?';

      $stmt = $dbh->prepare($image_delete);
      $stmt->bindValue(1,$product_id,PDO::PARAM_INT);
      $stmt->execute();

      $dbh->commit();

      $result = $product_name.'を削除しました';
      header('Location:../../ec_site/product_con.php?update='.$result.'');
      exit();

    } catch (PDOException $e) {
      $dbh->rollBack();
      echo $e->getMessage();
      exit();
    }
  }


?>