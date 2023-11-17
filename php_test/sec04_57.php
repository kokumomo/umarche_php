<?php
// クッキーは情報をブラウザで保存
// セッションはサーバーに保存
session_start();
?>

<html></html>
<head></head>
<body>
    <?php
    // セッションが設定されていなかったら
    if(!isset($_SESSION['visited'])){
        echo '初回訪問です';

        $_SESSION['visited'] = 1;
        $_SESSION['date'] = date('c');
    // セッションが設定されていたら
    } else {
        $visited = $_SESSION['visited'];
        $visited++;
        $_SESSION['visited'] = $visited;

        echo $_SESSION['visited'].'回目の訪問です<br>';
        // 日付があったら
        if(isset($_SESSION['date'])){
            echo '前回訪問は'.$_SESSION['date'].'です';
            $_SESSION['date'] = date('c');
        }

        // setcookie("id", 'aaa', time() - 1800, '/');

        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';

        echo '<pre>';
        var_dump($_COOKIE);
        echo '</pre>';
    }
    ?>
</body>