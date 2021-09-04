<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Имя');
            $table->string('surname')->comment('Фамилия');
            $table->string('patronymic')->comment('Отчество');
            $table->string('email')->comment('Электронная почта')->unique();
            $table->string('code')->comment('Код в мтуси, пример: 1БЭИ21012')->nullable()->unique();
            $table->string('phone', 11)->comment('Телефон')->nullable()->unique();
            $table->string('vk_id')->comment('ID пользователя в вконтакте')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
