<?php

session_start();
?>

<html></html>
<head></head>
<body>
    <?php
        echo 'セッション破棄しました';

        $_SESSION = [];
  
        if(isset($_COOKIE['PHPSESSID'])){
            // 空のsession idにからの情報を入れつつ、過去の情報を入れてから削除
            setcookie('PHPSESSID', '', time() - 1800, '/');
        }
        // session破棄
        session_destroy();

        echo 'セッション';
        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';

        echo 'クッキー';
        echo '<pre>';
        var_dump($_COOKIE);
        echo '</pre>';
   
    ?>
</body>