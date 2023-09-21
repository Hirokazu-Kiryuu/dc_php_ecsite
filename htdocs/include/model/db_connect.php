<?php
echo "model.php<br>";

/**
 * PHPDoc
 * DBに接続を行いPDOインスタンスを返す
 * @return object $pdo
 */

//DB接続関数
function dbConnect() {
  try {
    $dsn = DSN;
    $login_user = LOGIN_USER;
    $password = PASSWORD;
    $pdo = new PDO($dsn,$login_user,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo "接続成功<br>";
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit();
  }
  return $pdo;
}

?>
