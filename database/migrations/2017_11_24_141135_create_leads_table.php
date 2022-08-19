<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function(Blueprint $table)
        {
            $table->increments('id');            
            $table->integer('lead_owner')->unsigned();
            $table->string('first_name', 200)->nullable();
            $table->string('last_name', 200);
            $table->string('image', 200)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('email', 200)->unique()->nullable();
            $table->string('phone', 200)->nullable();
            $table->integer('lead_stage_id')->unsigned();            
            $table->integer('source_id')->unsigned()->nullable();            
            $table->string('company', 200)->nullable();
            $table->string('fax', 200)->nullable();
            $table->string('website', 200)->nullable();
            $table->integer('no_of_employees')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned();
            $table->float('annual_revenue', 10, 0)->unsigned()->nullable();
            $table->string('street', 200)->nullable();
            $table->string('city', 200)->nullable();
            $table->string('state', 200)->nullable();
            $table->string('zip', 200)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('timezone', 100)->nullable();
            $table->text('description', 65535)->nullable();
            $table->enum('access', ['private', 'public', 'public_rwd'])->default('public');
            $table->integer('converted_account_id')->unsigned()->nullable();
            $table->integer('converted_contact_id')->unsigned()->nullable();
            $table->float('position', 10, 0)->unsigned();
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
        Schema::dropIfExists('leads');
    }
}
