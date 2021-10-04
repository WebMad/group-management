<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccountAccountColumnHoursSubject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('education_history', function (Blueprint $table) {
            $table->boolean('account_hours')->default(true)->comment('Учитывать часы');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->boolean('account_hours')->default(true)->comment('Учитывать часы');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
