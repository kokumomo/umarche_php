<?php

// データベースに接続
require 'db_connection.php';

// ユーザー入力なし query
// $sql = 'SELECT * FROM contacts WHERE id = 2'; //SQL
// $sth = $dbh->query($sql); //SQL実行ステートメント


// ユーザー入力あり prepare, bind, execute 悪意ユーザ delete * SQLインジェクション対策
$sql = 'SELECT * FROM contacts WHERE id = :id'; //名前付きプレースホルダー
// $sth = $dbh->prepare($sql); //プレペアードステートメント
// $sth->bindValue('id', 2, PDO::PARAM_INT); //紐付け
// $sth->execute(); //実行


// トランザクション　まとまって処理 beginTransaction, commit, rollback
// ex)銀行 残高を確認->Aさんから引き落とし->Bさんに振り込み

$dbh->beginTransaction(); //トランザクションの開始

try {
    $sth = $dbh->prepare($sql); 
    $sth->bindValue('id', 2, PDO::PARAM_INT);
    $sth->execute();
    
    $dbh->commit();
}
catch( PDOException $Exception ) {
    $dbh->rollBack(); //ミスに気づき戻る
}

$result = $sth->fetchAll();
echo '<pre>';
var_dump($result);
echo '</pre>';



