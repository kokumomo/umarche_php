<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test; // Testモデルを使えるように読み込む
use Illuminate\Support\Facades\DB;



class TestController extends Controller
{
    public function index()
    {
        // Eloquent
        $values = Test::all(); //全件取得

        $count = Test::count(); // 数字

        $first = Test::findOrFail(1); // インスタンス

        // $whereBBB = Test::where('text', '=', 'bbb'); // Eloquent/Builder
        $whereBBB = Test::where('text', '=', 'bbb')->get(); // Collection
        
        // クエリビルダ
        $queryBuilder = DB::table('tests')->where('text', '=', 'bbb')->select('id', 'text')->get(); // コレクション型
        // DB::table('tests')->where('text', '=', 'bbb')->select('id', 'text'); // QueryBuilder
        
        
        dd($values, $count, $first, $whereBBB, $queryBuilder);

        // dd($values); // die + var_dump 処理を止めて内容を確認できる
        return view('tests.test', compact('values')); // viewはLaravelのヘルぱ関数 フォルダ名.ファイル名
    }
}
