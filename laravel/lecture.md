# 82. Laravel MVCモデルの記述方法1

### ルーティング→コントローラ→ビュー
```php
ルーティング　routes/web.php
use App\Http\Controllers\TestController; // ファイル内で使えるようにする
Route::get('tests/test', [TestController::class, 'index']); // 配列で書く

コントローラ　App/Http/Controllers/TestController.php
public function index()
{
    return view('tests.test'); // viewはLaravelのヘルパ関数 フォルダ名.ファイル名
}

ビュー　resources/views/test.blade.php // ファイル名.blade.phpと書く
test
```

# 83. Laravel MVCモデルの記述方法2

コントローラからモデルにアクセスしてデータベースの情報を取得

![img](public/img/01_27.png)

### コントローラ内でモデルを取得
```php
App/Http/Coctrollers/TestController.php
use App\Models\Test; // Testモデルを使えるように読み込む
public function index()
{
    $values = Test::all(); //全件取得

    // dd($values); // die + var_dump 処理を止めて内容を確認できる

    return view('tests.test', compact('values')); // compact関数でView側に変数を渡すと楽
}
```

### コントローラからビューへ
@foreach($values as $value)
{{ $value->id }}<br>
{{ $value->text }}<br>
@endforeach
´´´ 