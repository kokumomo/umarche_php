<?php
// キャッシュ：閲覧した閲覧ページ(html,css)を一時保存ファイル
// クッキー：入力されたユーザー情報(ID,pass)や閲覧サイト履歴がChromeやSafariなどのブラウザに残るファイル
// Cookieの仕組み（例）
// 1.ユーザーがWebサイトのフォームに「ログインID」と「パスワード」を入力
// 2.サイトのドメインからブラウザに、Cookie情報（セッションIDなど）を付与
// 3.ブラウザはそのCookie情報を保持
// 4.ユーザーが再度訪問したときに、ブラウザからCookie情報を戻してもらう
// 5.ユーザーがログイン情報を忘れていてもログインできる

// サードパーティーCookieの仕組み
// 1.Udemyサイトを閲覧
// 2.サイトにリターゲティング広告を表示するために設定されていた解析タグ(Js)が発火
// 3.リターゲティングの広告サーバーが識別IDを発行し、データーベースに保存
// 4.広告サーバーがブラウザ(Google)にIDを送付し、ブラウザがCookieに保存
// 5.Yahooを開くとUdemyの広告バナーが表示される。Yahooには広告配信スペースが設置されていてユーザーごとに最適な広告が配信される仕組みになっている。
// 6.ブラウザのCookieに保存されていたユーザーIDをもとに広告配信のリクエストが行われる。

// セッション：サーバー上に保存されるデータ
// Webアプリケーションで入力・処理されたデータを別の画面でも使うときにデータを引き渡す仕組み
// <input type="hidden">はhtmlにデータを保存していたのに対し、
// セッション変数はサーバー内にデータを保持
// 自分の保管領域にデータを出し入れするための専用キーが発行される(セッションID)
// ユーザーがアプリを操作している間だけデータを保持
// ユーザーが操作を終了した後も永続的に保持するにはデータベースで保管
session_start();

// クリックジャッキング：
header('X-FRAME-OPTIONS:DENY');

if(!empty($_SESSION)){
  echo '<pre>';
  var_dump($_SESSION);
  echo '</pre>';
}

// クロスサイトスクリプティング(XXS)攻撃：
// Webアプリケーションへのリクエストを偽造（フォージェリ）され、ユーザーの意図しない処理が実行されてしまう
// 1.ユーザーが正規のサイトにログインする（正規のWebアプリケーションAへのログイン）
// 2.ログインした状態のユーザーが、攻撃者によって作られた罠サイトを閲覧する
// 3.罠サイトに仕込まれたJavaScriptにより、正規のWebアプリケーションAのサーバーに対して「ユーザーパスワードを変更しなさい」というリクエストが送信される
// 4.正規のWebアプリケーションAのユーザーパスワードが変更される
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
    <!-- 合言葉が正しいか判定 -->
    <?php if($_POST['csrf'] === $_SESSION['csrfToken']) :?>
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
    <!-- ページフラグが1に変わる時点でcsrfが消えてしまうので値を保持 -->
    <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']) ;?>">
    </form>

    <?php endif; ?>

    <?php endif; ?>
    
    <?php if($pageFlag === 2) : ?>
    <!-- 合言葉が正しいか判定 -->
    <?php if($_POST['csrf'] === $_SESSION['csrfToken']) :?>
    送信が完了しました。

    <!-- 合言葉を削除 -->
    <?php unset($_SESSION['csrfToken']); ?>
    <?php endif; ?>
    <?php endif; ?>


    <?php if($pageFlag === 0) : ?>
    <?php
    // クロスサイトリクエストフォージュリ(CSRF)
    // ユーザーにリンクを踏ませて不正なリクエストを送信させる方法
    // 入力画面を表示するたびにcsrfTokenを作成しないようにif文で
    // セッションにcsrfTokenが設定されていなかったら合言葉を作る
    if(!isset($_SESSION['csrfToken'])){
      $csrfToken =  bin2hex(random_bytes(32));
      $_SESSION['csrfToken'] = $csrfToken;
    }
    $token = $_SESSION['csrfToken'];
    ?>

    <form method="POST" action="input.php">
    氏名
    <input type="text" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
    <br>
    メールアドレス
    <input type="email" name="email" value="<?php echo h($_POST['email']) ; ?>">
    <br>
    <input type="submit" name="btn_confirm" value="確認する">
    <input type="hidden" name="csrf" value="<?php echo $token;?>">
    </form>
    <?php endif; ?>

 </body>
</html>
