<?php
/**
 * viewのproduct_conのDBの必要値関数化
 * 
 **/

##簡易Ver
  function product_con_disp() {
    $stmt = product_disp();
      foreach($stmt as $key => $row) {

        $arr[] = $row;
    //     var_dump($row);
    //     print '<br>';
    //     print '<br>';
    // ##生成配列の長さを取得
    //     $arr_sum[] = $key;
    // ##stmt値代入
    //     $product_id[] = $row['product_id'];
    //     $product_name[] = $row['product_name'];
    //     $price[] = $row['price'];
    //     $stock_qty[] = $row['stock_qty'];
    //     $public_flg[] = $row['public_flg'];
    //     var_dump($product_id);
    //     print '<br>';

    //     $array = [
    //       $arr_sum[] = $key;
    //       $product_id[] = $row['product_id'];
    //       $product_name[] = $row['product_name'];
    //       $price[] = $row['price'];
    //       $stock_qty[] = $row['stock_qty'];
    //       $public_flg[] = $row['public_flg'];
    //     ];
      }
  return $arr;
    }
?>

