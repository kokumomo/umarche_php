<?php

function insertContact($request){

require 'db_connection.php';

// ユーザー入力あり DB保存 prepare, execute(配列(全て文字列))

$params = [
    'id' => null,
    'your_name' => $request['your_name'],
    'email' => $request['email'],
    'url' => $request['url'],
    'gender' => $request['gender'],
    'age' => $request['age'],
    'contact' => $request['contact'],
    'created_at' => null
];
// $params = [
//     'id' => null,
//     'your_name' => '名前3',
//     'email' => 'test@test.com',
//     'url' => 'http://test.com',
//     'gender' => '1',
//     'age' => '2',
//     'contact' => 'えええ',
//     'created_at' => null
// ];

$count = 0;
$columns = '';
$values = '';

foreach(array_keys($params) as $key){
    if($count++>0){
        $columns .= ',';
        $values .= ',';
    }
    $columns .= $key;
    $values .= ':'.$key;
}


$sql = 'INSERT INTO contacts ('. $columns .')values('. $values .')';
// var_dump($sql);

$sth = $dbh->prepare($sql); //プレペアードステートメント
$sth->execute($params); //実行

}