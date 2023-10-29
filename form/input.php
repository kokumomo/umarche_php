<?php

// クリックジャッキング：悪意のある埋め込まれた外部サイトページ上のボタンをクリックすると、
// 利用者に意図しない操作を実施させるといった行為を防ぐためのレスポンスヘッダー。
header('X-FRAME-OPTIONS:DENY');

// クロスサイトスクリプティング(XXS)攻撃：
// Webアプリケーションの入力フォームに不正なスクリプトコードを挿入し、
// 悪意のある操作を実行する攻撃　
// <script>alert('document.cookie')</script>とするとブラウザに保存されている
// Cookie情報としてセッションIDというサーバのデータにアクセスするための情報が取られてしまい、
// セッションハイジャックという攻撃手法と組み合わせることで、その人になりすまして
// アプリを利用できてしまう。
// CSRFと組み合わせてユーザに不正なリンクをクリックさせてXSSを実行し
// ユーザのセッションIDを奪われてしまう。
// htmlspecialchars関数でhtmlタグを無効化
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

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

    <title>Hello, world!</title>
  </head>
<body>

    <?php if($pageFlag === 1) : ?>
    <form method="POST" action="input.php">
    氏名:
    <?php echo h($_POST['your_name']) ;?>
    <br>
    メールアドレス:
    <?php echo h($_POST['email']) ;?>
    <br>
    <input type="submit" name="back" value="戻る">
    <input type="submit" name="btn_submit" value="送信する">
    <input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']) ;?>">
    <input type="hidden" name="email" value="<?php echo h($_POST['email']) ;?>">
    </form>

    <?php endif; ?>
    
    <?php if($pageFlag === 2) : ?>
    送信が完了しました。
    <?php endif; ?>


    <?php if($pageFlag === 0) : ?>
    <form method="POST" action="input.php">
    氏名
    <input type="text" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
    <br>
    メールアドレス
    <input type="email" name="email" value="<?php echo h($_POST['email']) ; ?>">
    <br>
    <input type="submit" name="btn_confirm" value="確認する">
    </form>
    <?php endif; ?>

 </body>
</html>
