<?php

echo "common.php<br>";

/**
 * PHPDoc
 * POSTの値を受け取りサニタイズ処理の結果を返す
 * 
 * @param string
 * @return array 
 */
  function h ($before) {
    foreach($before as $key => $value) {
      $after[$key] = htmlspecialchars($value,ENT_QUOTES,'UTF-8');
    }
    return $after;
  }

/**
 * 日付使い回し関数
 * 返り値：本日の日付
*/
function today() {
  $today = date("Ymd"); 
  return $today;
}

/**
 * 配列の合計数（金額）を計算すru
 * @param int $x 左辺
 * @param int $y 右辺
 * @param array $detect配列のkey
 * @return int $sum $xと$y+繰り返しの合計値
*/

function price_sum($x,$y,$arr_sum) {
  foreach($arr_sum as $val) {
    $cal[] = $x[$val] * $y[$val];
    var_dump($cal);
    print '<br>';
  }
  $sum = array_sum($cal);
  return $sum;
}
//使用例
// $val = price_sum($price,$product_qty,$detect);
// var_dump($val);

?>