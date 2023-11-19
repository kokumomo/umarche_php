<?php

class Product{
    // アクセス修飾子, private(外からアクセスできない), protected(自分・継承したクラス), public(公開)

    // 変数
    private $product = [];

    // 関数
    function __construct($product){

        $this->product = $product;
    }

    public function getProduct(){
        // Productクラスのproductを呼び出す
        echo $this->product;
    }

    public function addProduct($item){
        // Productクラスのproductを呼び出しつつ、引数を追加(.=)
        $this->product .= $item;
    }

    // public static function getStaticProduct($str){
    //     echo $str;
    // }
}

// インスタンス化、引数'テスト'は__constructの$productに入る
$instance = new Product('テスト');

// var_dump($instance);

// 関数getProductを呼び出す
$instance->getProduct();
echo '<br>';

$instance->addProduct('追加分');
echo '<br>';
$instance->getProduct();
echo '<br>';

$instance->getProduct();
echo '<br>';

// 静的(static) クラス名::関数名
// Product::getStaticProduct('静的');
// echo '<br>';