<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function(Blueprint $table)
        {
            $table->increments('id');            
            $table->float('position', 10, 0)->unsigned();
            $table->string('name', 100);
            $table->string('code', 3);    
            $table->boolean('base')->default(0);        
            $table->float('exchange_rate', 10, 0)->unsigned();
            $table->integer('face_value')->unsigned()->default(1);
            $table->string('symbol', 50);
            $table->enum('symbol_position', ['before', 'after'])->default('before');
            $table->string('decimal_separator', 3)->default('.');
            $table->string('thousand_separator', 3)->default(',');          
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
        Schema::dropIfExists('currencies');
    }
}
