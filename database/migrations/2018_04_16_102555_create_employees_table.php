<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id'); //primary-key , unsigned , unique, auto-increment 
            $table->string('first_name');
            $table->string('last_name');
            $table->string('user_image');
            $table->text('job');
            $table->unsignedInteger('user_id');
            $table->enum('status',['Active','Not Active'])->default('Not Active');
            $table->point('location');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
