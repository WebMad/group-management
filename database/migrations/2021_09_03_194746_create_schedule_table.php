<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->comment('ИД предмета')->constrained('subjects');
            $table->string('start_time')->comment('Время начала пары hh:ss');
            $table->string('end_time')->comment('Время окончания пары hh:ss');
            $table->boolean('parity')->nullable()->comment('По четным неделям');
            $table->integer('day_of_week')->default(0)->comment('День недели');
            $table->integer('start_week')->comment('С какой недели');
            $table->integer('end_week')->comment('До какой недели');
            $table->string('address')->comment('Адрес и кабинет');
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
        Schema::dropIfExists('schedule');
    }
}
