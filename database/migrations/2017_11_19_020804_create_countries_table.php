<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('ascii_name', 100);
            $table->char('code', 2)->unique();
            $table->char('iso3', 3);
            $table->integer('iso_numeric')->unsigned();                      
            $table->string('capital', 100);
            $table->string('currency_code', 3);
            $table->string('phone', 10);
            $table->char('fips', 2); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
