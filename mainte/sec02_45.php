<?php

// パスワードを記録したファイルの場所
echo __FILE__;
// /Applications/MAMP/htdocs/umarche_php/mainte/test.php

echo '<br>';
// パスワード(暗号化)
echo(password_hash('password123', PASSWORD_BCRYPT));
// $2y$10$yRA76zSZ3.k83fV9cX/1ZuPzWWVtpHmDHF68e8WXuVCne1RUHm9I2