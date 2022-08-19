<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_sheets', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('linked_id')->unsigned();
            $table->enum('linked_type', ['estimate', 'invoice']);
            $table->string('item', 200);
            $table->float('quantity', 10, 0)->unsigned();
            $table->string('unit', 10);
            $table->float('rate', 10, 0)->unsigned();
            $table->float('tax', 10, 0)->unsigned();
            $table->float('discount', 10, 0)->unsigned();
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
        Schema::dropIfExists('item_sheets');
    }
}
