<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test; // Testモデルを使えるように読み込む


class TestController extends Controller
{
    public function index()
    {
        $values = Test::all(); //全件取得
        // dd($values); // die + var_dump 処理を止めて内容を確認できる
        return view('tests.test', compact('values')); // viewはLaravelのヘルぱ関数 フォルダ名.ファイル名
    }
}
