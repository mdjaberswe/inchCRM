<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('account_owner')->unsigned();
            $table->string('account_name', 200);
            $table->string('image', 200)->nullable();
            $table->string('account_email', 200)->nullable();
            $table->string('account_phone', 200)->nullable();
            $table->integer('account_type_id')->unsigned()->nullable();
            $table->integer('industry_type_id')->unsigned()->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
