<?php


ini_set("display_errors", 1);
error_reporting(E_ALL);

// インターフェース
interface ProductInterface{
    // public function echoProduct(){
    //     echo '親クラスです';
    // }

    public function getProduct();
}

interface NewsInterface{
    // public function echoProduct(){
    //     echo '親クラスです';
    // }

    public function getProduct();
}

// 具象クラス
class BaseProduct{
    public function echoProduct(){
        echo '親クラスです';
    }

    // オーバーライド(上書き)
    public function getProduct(){
        echo '親の関数です';
    }
}

// 子クラス
class Product implements ProductInterface, NewsInterface {

    // 変数
    private $product = [];

    // 関数
    function __construct($product){

        $this->product = $product;
    }

    public function getProduct(){
        echo $this->product;
    }

    public function getNews(){
        echo 'ニュースです';
    }

}

$instance = new Product('テスト');

// 親クラスのメソッド
// $instance->echoProduct();
// echo '<br>';

$instance->getProduct();
echo '<br>';

$instance->getNews();
echo '<br>';
