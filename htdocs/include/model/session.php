<?php

echo "session.php<br>";

/**
 * ログインページへリダイレクト
 * @param int num
*/

function index_redirect($num) {
  if(empty($num)) {
    // echo "仮止めログインページへ遷移<br>";
    // header('Location:../../0002/ec_site/index.php?');
    exit();
  }
}

/**
 * 商品一覧ページへリダイレクト
 * @param int $num
 * 
*/

function list_redirect($num) {
  if($num > 1) {
    // echo "仮止め一覧ページへ遷移<br>";
    // header('Location:../../0002/ec_site/product_list.php');
    exit();
  }
}

?>