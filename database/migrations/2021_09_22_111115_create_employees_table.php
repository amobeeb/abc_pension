<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('surname');
            $table->string('firstname');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->text('address');
            $table->string('password');
            $table->integer('otp')->nullable();
            $table->boolean('isActivate')->default(0);
            $table->string('account_number')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
