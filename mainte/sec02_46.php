<?php
// PHPでデータを保存する方法
// ファイル(テキストファイル)→手軽・データのやり取り
// データベース→大量のデータを保管

$contactFile = '.contact.dat';

// ファイル名型(ファイル丸ごと)→file_get_contents
$fileContents = file_get_contents($contactFile);

// echo $fileContents;

// ファイルに書き込み(上書き)→file_put_contents
// file_put_contents($contactFile, 'テストです');

// $addText = 'テストです' . "\n";

// ファイルに書き込み(追記)
// file_put_contents($contactFile, $addText, FILE_APPEND);

// 配列 file ,区切る explode, foreach
$allData = file($contactFile);

foreach($allData as $lineData){
    $lines = explode(',', $lineData);
    echo $lines[0], '<br>';
    echo $lines[1], '<br>';
    echo $lines[2], '<br>';
}