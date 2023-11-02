<?php
// inputが入力されたPOSTの値($request)になって,$errorsという配列でreturnされる

function validation($request){ //$_POST連想配列

// エラーをまとめて保管する為の配列
$errors = [];

// your_nameが空だったら配列に値を追加
if(empty($request['your_name'])){
    $errors[] = '氏名は必須です';
}

return $errors;


}

?>