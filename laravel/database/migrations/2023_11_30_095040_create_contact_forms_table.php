<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_forms');
    }
};
