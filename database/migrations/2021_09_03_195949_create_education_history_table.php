<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->comment('ИД предмета')->constrained('subjects');
            $table->timestamp('start_date')->comment('Дата начала пары');
            $table->timestamp('end_date')->comment('Дата окончания пары');
            $table->foreignId('teacher_id')->comment('ИД преподавателя')->constrained('teachers');
            $table->boolean('filled')->default(false)->comment('Заполнены ли посещения');
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
        Schema::dropIfExists('education_history');
    }
}
