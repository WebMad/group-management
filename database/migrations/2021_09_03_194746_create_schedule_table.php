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
            $table->foreignId('scheme_id')->constrained('schedule_scheme');
            $table->integer('week_type')->nullable()->comment('По каким неделям');
            $table->integer('day_of_week')->default(0)->comment('День недели');
            $table->integer('start_week')->nullable()->comment('С какой недели');
            $table->integer('end_week')->nullable()->comment('До какой недели');
            $table->string('address')->nullable()->comment('Адрес и кабинет');
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
