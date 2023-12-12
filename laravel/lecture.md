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

# 103. Create 新規登録 レイアウト調整

resources/views/contacts/index.blade.php
```php
<<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お問い合わせ一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("いんでっくす") }}<br>
                    <a href="{{ route('contacts.create') }}">新規登録</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

routes/web.php
```php
Route::prefix('contacts')
->middleware(['auth'])
->controller(ContactFormController::class)
->name('contacts.')
->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
});
```
app/Http/Controllers/ContactFormController.php
```php
public function create()
    {
        return view('contacts.create');
    }
```

resources/views/contacts/create.blade.php
```php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新規作成') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("くりえいと") }}<br>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

### Index と create を作ってみる
フォームレイアウトは TailBlocks を参照  
Contact の3つ目

<br>

# 104. Createフォーム

### Create.blade.php

```php
<form method="post" action="{{ route('contacts.store') }}">
 // 氏名、件名、メールアドレス、ホームページ、性別、年齢、お問い合わせ内容、注意事項に同意する
<select name="age">
   <option value="">選択してください</option>
   <option value="1">〜19歳</option>
   <option value="2">20歳〜29歳</option>
   <option value="3">30歳〜39歳</option>
   <option value="4">40歳〜49歳</option>
   <option value="5">50歳〜59歳</option>
   <option value="6">60歳〜</option>
</select>
</form>
```

<br>

# 105. Store Requestクラス

route/web.php
```php
Route::prefix('contacts')
->middleware(['auth'])
->controller(ContactFormController::class)
->name('contacts.')
->group(function() {
Route::get('/', 'index')->name('index');
Route::get('/create', 'create')->name('create');
Route::post('/', 'store')->name('store');
});
```
### コントローラ
ContactFormController.php  
フォーム登録・・PHPでは$_POSTなど。LaravelではRequestクラス  
引数に(Request $request)  
DI(Dependency Injection 依存性の注入)  
(メソッドインジェクションとも呼ばれる)  
Requestをインスタンス化したものが入ってくる  
Requestクラス自体はvendor/laravel/framework/src/Illuminate/Http/Request.php  

app/Http/Controllers/ContactFormController.php
```php
public function store(Request $request)
{
    dd($request, $request->name);
}
```

app/Http/Controllers/ContactFormController.php
```php
<section class="text-gray-600 body-font relative">
    <form method="post" action="{{ route('contacts.store') }}">
        @csrf
```
<br>

# 106. Store 保存

app/Models/ContactForm.php
```php
class ContactForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'email',
        'url',
        'gender',
        'age',
        'contact'
    ];
}
```

app/Http/Controllers/ContactFormController.php
```php
public function store(Request $request)
    {
        // dd($request, $request->name);

        ContactForm::create([
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'url' => $request->url,
            'gender' => $request->gender,
            'age' => $request->age,
            'contact' => $request->contact,
        ]);

        return to_route('contacts.index');
    }

```