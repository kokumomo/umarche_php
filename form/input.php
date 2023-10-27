<?php

// スーパーグローバル変数 php 9種類
// 連想配列
if(!empty($_POST)){
  echo '<pre>';
  var_dump($_POST) ;
  echo '</pre>';
}

// 入力、確認、完了 input.php, confirm.php, thanks.php
// input.php

// 変数によって表示する画面を切り替える
// 0:入力,1:確認,2:完了
$pageFlag = 0;

if(!empty($_POST['btn_confirm'])){
    $pageFlag = 1;
}
if(!empty($_POST['btn_submit'])){
    $pageFlag = 2;
}

?>
<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> -->

    <title>Hello, world!</title>
  </head>
<body>

    <?php if($pageFlag === 1) : ?>
    <form method="POST" action="input.php">
    氏名:
    <?php echo $_POST['your_name'] ;?>
    <br>
    メールアドレス:
    <?php echo $_POST['email'] ;?>
    <br>
    <input type="submit" name="btn_submit" value="送信する">
    <input type="hidden" name="yourname" value="<?php echo $_POST['your_name'] ;?>">
    <input type="hidden" name="email" value="<?php echo $_POST['email'] ;?>">
    </form>

    <?php endif; ?>
    
    <?php if($pageFlag === 2) : ?>
    送信が完了しました。
    <?php endif; ?>


    <?php if($pageFlag === 0) : ?>
    <form method="POST" action="input.php">
    氏名
    <input type="text" name="your_name">
    <br>
    メールアドレス
    <input type="email" name="email">
    <br>
    <input type="submit" name="btn_confirm" value="確認する">
    </form>
    <?php endif; ?>

 </body>
</html>
