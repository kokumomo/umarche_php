<?php

// 抽象クラス　設定するメソッドを強制
abstract class ProductAbstract{
    public function echoProduct(){
        echo '親クラスです';
    }

    // 抽象メソッド
    abstract public function getProduct();
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
class Product extends ProductAbstract {

    // 変数
    private $product = [];

    // 関数
    function __construct($product){

        $this->product = $product;
    }

    public function getProduct(){
        echo $this->product;
    }

}

$instance = new Product('テスト');

// 親クラスのメソッド
$instance->echoProduct();
echo '<br>';

$instance->getProduct();
