<?php

// スーパーグローバル変数 php 9種類
// 連想配列
if(!empty($_POST)){
  echo '<pre>';
  var_dump($_POST) ;
  echo '</pre>';
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
    <form method="POST" action="input.php">
        氏名
        <input type="text" class="form-control" id="your_name" name="your_name">
        <br>
        <input type="checkbox" name="sports[]" value="野球">野球
        <input type="checkbox" name="sports[]" value="サッカー">サッカー
        <input type="checkbox" name="sports[]" value="バスケ">バスケ

        <input type="submit" value="送信">
    </form>

 </body>
</html>
