# 94. 概要モデル・マイグレーション

### 簡易Webアプリ
   お問い合わせフォーム拡張版(CRUD)  
   REST (RESTfulを活用)  
   バリデーション  
   ダミーデータ(シーダー/ファクトリー)　　
   ベージネーション　　
   簡易検索機能　　
   Model -> Route -> Controller -> View

### モデル・マイグレーション
   -m でマイグレーションも同時作成  
   モデルは単数形 マイグレーションファイルは複数形で生成される  
   php artisan make:model ContactForm -m

### xxx_create_contact_forms.php
```php
public function up()
      { Schema::create('contact_forms', function (Blueprint $table) {
         $table->id();
         $table->string('name', 20); // 氏名
         $table->string('email', 255); // メールアドレス
         $table->longText('url')->nullable(); // url null可
         $table->boolean('gender'); // 性別
         $table->tinyInteger('age'); // 年齢
         $table->string('contact', 200); // お問い合わせ内容
         $table->timestamps(); });

      }
```
php artisan migrate

<br>

# 95. マイグレーション・追加トロールバック

### マイグレーションは履歴管理
後から列を追加・削除なども履歴を残せる  
php artisan make:migration add_title_to_contact_forms_table  
```php
public function up() //追加
   { Schema::table('contact_forms', function (Blueprint $table) {
      $table->string('title', 50)->after('name'); });
   }

public function down() // ロールバック
   { Schema::table('contact_forms', function (Blueprint $table) {
      $table->dropColumn('title');});
   }
```

php artisan make:migration add_title_to_contact_forms_table

   php artisan migrate // マイグレーション実施  
   php artisan migrate:status // 状態表示  
   php artisan migrate:rollback // 一つ戻す  
   php artisan migrate:rollback --step=2 // 2つ戻す  
   php artisan migrate:refresh // ロールバックして再実行  
   php artisan migrate:fresh // テーブル削除して再実行  

<br>

# 96. 以前書いていたコードの復元

LaravelBreezeインストールしたことでrouteファイルが書き変わっている  
routes/web.php  
use App\Http\Controllers\TestController

Route::get('tests/test', [ TestController::class, 'index']);  

migrate:freshするとDB内データが全て削除されるので事前に　　
ダミーデータを作っておくのが一般的  

<br>

# 97. RestFulなコントローラー  

### リソースコントローラー
コントローラ側でよく使うメソッドをまとめて作る仕組み  
php artisan make:controller ContactFormController --resource  

| 動詞  | URL | アクション      | ルート名 | 
| :-: | -------- | ---------: | -------: | 
| GET | /contacts | index | photes.index | 
| GET | /contacts/create | create | photes.create | 
| POST| /contacts | store | photes.store | 
| GET | /contacts/(contact) | show | photes.show | 
| GET | /contacts/(contact)/edit | edit | photes.edit | 
| PUT/PATCH | /contacts/(contact) | update | photes.update | 
| DELETE | /contacts/(contact) | destroy | photes.destroy | 

use App\Http\Controllers\ContactFormController;
Route::resource('contacts', ContactFormController::class);

<br>

# 98. ルーティング(グループ・認証)

### routes/web.php
```php
use App\Http\Controllers\ContactFormController.php

// 1行ずつ書いた場合
Route::get('contacts', [ContactFormController::class, 'index'])->name('contacts.index');

// グループ化してまとめるとシンプルに書ける
Route::prefix('contacts') // 頭にcontactsをつける(フォルダ名)
   ->middleware(['auth']) // 認証
   ->controller(ContactFormController::class) // コントローラ指定
   ->group(function() {// グループ化
      Route::get('/', 'index')->name('index'); // 名前付きルート
});
```
app/Http/Controller/ContactFormController.php  
```php
 public function index()
    {
        return view('contacts.index');
    }
```
resources/views/contacts/index.blade.php  
```php
<p>contacts.index</p>
```

<br>

# 99. Bladeコンポーネントについて(login.blade.php)

### auth/login.blade.php　　
頭にx-とつくのはBladeコンポーネント(部品)  

クラスを使うパターン  
先にresources側を見て、なかったらクラス側も見てみる  
app/View/Components/GuestLayout.php  

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public function render(): View
    {
        return view('layouts.guest');
    }
}
```

<br>

# 100. スロット、名前付きスロットなど

### スロット  
```php
ヘッダー・フッター共通の箇所をまとめたり  
一部だけ他の表示に差し替えたりできる機能

layouts/guest.blade.phpの {{ $slot }} は  
resources/views/auth/login.blade.phpの<x-guest-layout>にあたる  


<x-auth-card>
   <x-slot name="logo"> 名前付きスロット
</x-auth-card>
x-auth-card.blade.php
{{ $logo }} 名前付きスロット
```

<br>

# 101. form, 多言語, tailwindcss
```php

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}"> //actionはformの飛び先
        @csrf
```

<br>

# 102. app.blade.php と navigation.blade.php

app/View/Components/AppLayout.php   
```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function render(): View
    {
        return view('layouts.app');
    }
}
```


resource/views/laytouts/app.blade.php  
```php
<body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
```


resources/views/layouts/navigation.blade.php

