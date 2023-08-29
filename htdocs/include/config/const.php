<?php
echo "const.php<br>";
##DB接続定数定義
define("DSN", 'mysql:host=localhost;dbname=dc_ec_site;charset=utf8');
define("LOGIN_USER", 'root');
define("PASSWORD", 'root');
##画像ファイルパス
define("IMAGE_PATH",'../../htdocs/ec_site/images/');
##エラーメッセージ
define("LOGIN_ERR",'ユーザー名またはパスワードが間違っています');
##ログイン成功メッセージ(仮)
define("LOGIN_TRUE",'ログインに成功しました');
##ログアウトメッセージ
define("LOGOUT_M","ログアウトしました");

#C#ookieの時間
define('EXPIRATION_PERIOD',30);

?>