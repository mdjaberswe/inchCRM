<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('image', 200)->nullable();
            $table->string('title', 200);
            $table->enum('employee_type', ['full_time', 'part_time', 'casual', 'fixed_term', 'probation'])->default('full_time')->nullable();
            $table->string('phone', 200)->nullable();
            $table->date('birthdate')->nullable();
            $table->date('date_of_hire')->nullable();
            $table->string('fax', 200)->nullable();
            $table->string('website', 200)->nullable();
            $table->string('street', 200)->nullable();
            $table->string('city', 200)->nullable();
            $table->string('state', 200)->nullable();
            $table->string('zip', 200)->nullable();
            $table->string('timezone', 100)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->text('settings', 65535)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staffs');
    }
}
