# 113. サービスへの切り離し(ファットコントローラー防止)

# サービス作成
App/Services/CheckFormService.php
```php
namespace App\Services;

class CheckFormService
{
    public static function checkGender($data){
        if($data->gender === 0 ){
            $gender = '男性';
            $gender = '女性';
        }
        return $gender;
    }

    public static function checkAge($data){
        if($data->age === 1){ $age = '〜19歳'; }
        if($data->age === 2){ $age = '20歳〜29歳'; }
        return $age;
    }
}
```
app/Http/Controllers/ContactFormController.php
```php
use App\Services\CheckFormService;

 public function show($id)
    {
        $contact = ContactForm::find($id); // 1件だけ取得

        $gender = CheckFormService::checkGender($contact);

        $age = CheckFormService::checkAge($contact);

        return view('contacts.show', compact('contact', 'gender', 'age'));
    }
```

<br>

# 114. バリデーション(フォームリクエスト)
入力データを検証したり、アクセスしたユーザーがそのリクエストを実行する権限があるかを確認  

バリデーションはコントローラに書いても良いが、    
リクエストを使うとファイル分離してスッキリ書ける  

php artisan make:request StoreContactRequest  
App/Http/Requests/StoreContactRequest.php
```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'], 
            'url' => ['url', 'nullable'],
            'gender' => ['required', 'boolean'],
            'age' => ['required'],
            'contact' => ['required', 'string', 'max:200'],
            'caution' => ['required', 'accepted']
        ];
    }
}

```

App/Http/Controllers/ContactFormController.php
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactForm;
use App\Services\CheckFormService;
use App\Http\Requests\StoreContactRequest;

class ContactFormController extends Controller
{
    public function store(StoreContactRequest $request)
    {
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
}
```

resources/views/contacts/create.blade.php
```php
<section class="text-gray-600 body-font relative">
    <x-input-error :messages="$errors->all()" class="mt-2" />
    <form method="post" action="{{ route('contacts.store') }}">
        @csrf
```

<br>

# 115. oldへルパ関数  

バリデーションで弾かれると入力した情報が消えてしまう  
old()をつけることで値を保持
```php
<input type="text" id="title" name="title" value="{{ old('name') }}">

<input type="radio" name="gender" value="0" {{ old('gender') == 0 ? 'checked' : '' }}>男性

<option value="1" {{ old('age') == 1 ? 'selected' : '' }}>〜19歳</option>
```
