<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passers', function (Blueprint $table) {
            $table->increments('id');
            $table->char('examinee_id', 100);
            $table->string('name_of_examinee');
            $table->string('campus_eligibility', 100);
            $table->string('school');
            $table->string('division', 150);
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
        Schema::dropIfExists('passers');
    }
}
