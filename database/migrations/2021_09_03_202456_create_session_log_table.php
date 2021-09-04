<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eh_id')
                ->comment('Ссылка на проведенную пару')
                ->constrained('education_history');
            $table->foreignId('student_id')->comment('ИД студента')->constrained('students');
            $table->boolean('attend')->default(false)->comment('Явка');
            $table->boolean('valid_reason')
                ->default(false)
                ->comment('Пропуск по уважительной причине');
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
        Schema::dropIfExists('session_log');
    }
}
