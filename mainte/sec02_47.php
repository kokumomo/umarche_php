<?php
// PHPでデータを保存する方法
// ファイル(テキストファイル)→手軽・データのやり取り
// データベース→大量のデータを保管

// ファイル操作の方法
// ストリーム型(1行毎)→fopen, fclose, fgets, fwrite
// オブジェクト型(オブジェクトとして)→SplFileObject

// ファイル操作の流れ(ストリーム型)
// 1. 開く fopen(r,w,a)
// 2. 排他ロック flock
// 3. 読込/書込/追記 fgets/fwrite
// 4. 閉じる fclose(ロック解除)


$contactFile = '.contact.dat';

$contents = fopen($contactFile, 'a+');

$addText = '１行追記' . "\n";

fwrite($contents, $addText);

fclose($contents);