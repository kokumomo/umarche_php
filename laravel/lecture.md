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

<br>

# 107. index画面、ナビゲーション追加

### Index

データベースから情報取得  
ContactformController.php
```php
public function index()
{ $contacts = ContactForm::select('id', 'name', 'title', 'created_at')->get();
return view('contacts.index', compact('contacts'));
}
```

resources/views/contacts/index.bladde.php  
```php
@foreach($contacts as $contact)
{{ $contact->id }} {{ $contact->name}}  {{ $contact->title}}  {{ $contact->created_at}}
@endforeach
```

<br>

# 108. show詳細表示画面 その１

### Show
ルート->コントローラー->ビュー  
routes/web.php  
```php
略->group(function(){
    Route::get('/{id}', 'show')->name('show');//
});  
```

ContactFormController.php  
```php
public function show($id) // 引数にid
{
    $contact = ContactForm::find($id); // 1件だけ取得
    return view('contacts.show', compact('contact'));
}
```

いったん新規に作成した詳細画面にデータベースからidで紐付けた情報を表示させる  
resources/views/contacts/show.blade.php
```php
{{ $contact->id }} {{ $contact->name }}// 1件なのでforeachは不要
```

詳細画面に遷移するリンクを作成  
resources/views/contacts/index.blade.php  
```php
<a href="{{ route('contacts.show', ['id' => $contact->id]) }}">詳細を見る</a>
```

<br>

# 109. show詳細表示画面 その2

### 詳細画面のフォームにデータベースに入っている情報を表示させる
{{ $contact->id }} {{ $contact->name }}で表示が可能  

resources/views/contacts/show.blade.php
```php
<div>
    <label for="name">氏名</label>
    <div>{{ $contact->name }}</div>            

    <label for="title">件名</label>
    <div>{{ $contact->title }}</div>            

    <label for="email">メールアドレス</label>
    <div>{{ $contact->email }}</div>            

    <label for="url">ホームページ</label>
    @if($contact->url) //urlの有無を判定
    <div>{{ $contact->url }}</div>
    @endif            

    <label>性別</label><br>
    <div>{{ $gender }}</div>

    <label for="age">年齢</label>
    <div>{{ $age }}</div>

    <label for="contact">お問い合わせ内容</label>
    <div>{{ $contact->contact }}</div>

    <button>新規登録する</button>
</div>
```

### 性別・年齢の表示を変える
app/Http/Controllers/ContactFormController.php
```php
 public function show($id)
    {
        $contact = ContactForm::find($id); // 1件だけ取得

        if($contact->gender === 0 ){
            $gender = '男性';
        } else {
            $gender = '女性';
        }

        if($contact->age === 1){ $age = '〜19歳'; }
        if($contact->age === 2){ $age = '20歳〜29歳'; }
        if($contact->age === 3){ $age = '30歳〜39歳'; }
        if($contact->age === 4){ $age = '40歳〜49歳'; }
        if($contact->age === 5){ $age = '50歳〜59歳'; }
        if($contact->age === 6){ $age = '60歳〜'; }

        return view('contacts.show', compact('contact', 'gender', 'age'));
    }
```

<br>

# 110. edit 編集画面

### Edot ルート・コントローラ
ルート->コントローラ->ビュー  
routes/web.php  
```php
Route::get('/{id}/edit', 'edit')->name('edit');//
```

resources/views/contacts/show.blade.php
```php
<form mehtod="get" action="{{ route('contacts.edit', ['id' => $contact->id ])}}">
<div class="p-2 w-full">
    <button>編集する</button>
</div>
</form>
```

ContactFormController.php  
```php
public function edit($id) // 引数にid
{
    $contact = ContactForm::find($id); // 1件だけ取得
    return view('contacts.edit', compact('contact'));
}
```

resources/views/contacts/edit.blade.php
```php
<label for="name">氏名</label>
<input type="text" id="name" name="name" value="{{ $contact->name }}">

<label for="title">件名</label>
<input type="text" id="title" name="title" value="{{ $contact->title }}">

<label for="email">メールアドレス</label>
<input type="email" id="email" name="email" value="{{ $contact->email }}">

<label for="url">ホームページ</label>
<input type="url" id="url" name="url" value="{{ $contact->url }}">

// 年齢と性別はタグの中に@ifで判定を入れる
<label>性別</label><br>
<input type="radio" name="gender" value="0" @if($contact->gender == 0) checked @endif>男性
<input type="radio" name="gender" value="1" @if($contact->gender == 1) checked @endif>女性

<label for="age">年齢</label>
<select name="age">
    <option value="">選択してください</option>
    <option value="1" @if($contact->age == 1) selected @endif>〜19歳</option>
    <option value="2" @if($contact->age == 2) selected @endif>20歳〜29歳</option>
    <option value="3" @if($contact->age == 3) selected @endif>30歳〜39歳</option>
    <option value="4" @if($contact->age == 4) selected @endif>40歳〜49歳</option>
    <option value="5" @if($contact->age == 5) selected @endif>50歳〜59歳</option>
    <option value="6" @if($contact->age == 6) selected @endif>60歳〜</option>
</select>

<label for="contact">お問い合わせ内容</label>
<textarea id="contact" name="contact">{{ $contact->contact }}</textarea>

<button>新規登録する</button>
```

<br>

# 111. update 更新画面

ルート->コントローラ->ビュー  
routes/web.php  
```php
Route::post('/{id}/update', 'update')->name('update');
```

ContactFormController.php  
```php
public function update($id) 
{
    $contact = ContactForm::find($id);
    // フォームに入ってきた情報をデータベースに登録(上書き保存)
    $contact->name = $request->name;
    $contact->title = $request->title;
    $contact->email = $request->email;
    $contact->url = $request->url;
    $contact->gender = $request->gender;
    $contact->age = $request->age;
    $contact->contact = $request->contact;
    $contact->save();

    return to_route('contacts.index');
}
```

resources/views/contacts/edit.blade.php
```php
<form method="post" action="{{ route('contacts.update', ['id' => $contact->id ]) }}">
```

<br>

# 112. destroy 削除機能

ルート->コントローラ->ビュー  
routes/web.php  
```php
Route::post('/{id}/destroy', 'destroy')->name('destroy');
```

ContactFormController.php  
```php
public function destroy($id)
    {
        $contact = ContactForm::find($id);
        $contact->delete();

        return to_route('contacts.index');
    }
```

resources/views/contacts/show.blade.php
```php
    <form id="delete_{{ $contact->id }}" class="mt-40" method="post" action="{{ route('contacts.destroy', ['id' => $contact->id ])}}">
    @csrf
    <div class="p-2 w-full">
    <a href="#" data-id="{{ $contact->id }}" onclick="deletePost(this)" class="flex mx-auto text-white bg-pink-500 border-0 py-2 px-8 focus:outline-none hover:bg-pink-600 rounded text-lg">削除する</a>
    </div>
</form>

<!-- 確認メッセージ -->
<script>
    function deletePost(e){
        'use strict'
        if(confirm('本当に削除してよろしいですか？')){
            document.getElementById('delete_' + e.dataset.id).submit()
        }
    }
</script>
```
