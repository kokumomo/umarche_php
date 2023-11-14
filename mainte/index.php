<?php

// データベースに接続
require 'db_connection.php';

// ユーザー入力なし query
// $sql = 'SELECT * FROM contacts WHERE id = 2'; //SQL
// $stmt = $pdo->query($sql); //SQL実行ステートメント
// // fetch all rows into array, by default PDO::FETCH_BOTH is used


// ユーザー入力あり prepare, bind, execute 悪意ユーザ delete * SQLインジェクション対策
$sql = 'SELECT * FROM contacts WHERE id = :id'; //名前付きプレースホルダー
$stmt = $pdo->prepare($sql); //プレペアードステートメント
$stmt->bindValue('id', 2, PDO::PARAM_INT); //紐付け
$stmt->execute(); //実行

$result = $stmt->fetchAll();
echo '<pre>';
var_dump($result);
echo '</pre>';
