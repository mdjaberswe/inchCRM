<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function(Blueprint $table)
        {
            $table->increments('id'); 
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('contact_owner')->unsigned();            
            $table->integer('account_id')->unsigned();
            $table->string('first_name', 200)->nullable();
            $table->string('last_name', 200);     
            $table->string('image', 200)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('phone', 200)->nullable(); 
            $table->string('fax', 200)->nullable();
            $table->string('website', 200)->nullable();
            $table->integer('contact_type_id')->unsigned()->nullable();
            $table->integer('source_id')->unsigned()->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
