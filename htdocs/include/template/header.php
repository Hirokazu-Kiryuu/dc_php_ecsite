<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="../css/header.css"> -->
</head>
<body>
  <header>
    <!-- <div class="left"> -->
      <h1>EC SITE</h1>
    <!-- </div> -->
    <?php if($form_display === TRUE) :?>
    <div class="side">
      <!-- フォーム設置 -->
      <a href="../ec_site/cart.php">カート</a>
      <form action="../include/model/logout.php" method="POST">
        <input type="hidden" name="logout" value="logout">
        <input type="submit" value="ログアウト">
      </form>
    <?php endif;?>

    <?php if($form_display === "thanks") :?>
      <div class="side">
      <!-- フォーム設置 -->
      <form action="../include/model/cart_delete.php" method="POST">
        <input type="hidden" name="delete" value="delete">
        <input type="submit" value="商品一覧">
      </form>
      <form action="../include/model/logout.php" method="POST">
        <input type="hidden" name="logout" value="logout">
        <input type="submit" value="ログアウト">
      </form>
      <!-- <a href="../ec_site/product_list.php">商品一覧</a> -->
    <?php endif;?>

      <!-- <form action="../include/model/logout.php" method="POST">
        <input type="hidden" name="logout" value="logout">
        <input type="submit" value="ログアウト">
      </form> -->

    </div>

  </header>
</body>
</html>