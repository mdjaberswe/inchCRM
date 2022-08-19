<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('linked_id')->unsigned();
            $table->string('linked_type', 200);
            $table->float('quantity', 10, 0)->unsigned();
            $table->string('unit', 10);
            $table->float('rate', 10, 0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
