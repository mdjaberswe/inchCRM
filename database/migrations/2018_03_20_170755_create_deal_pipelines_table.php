<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealPipelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_pipelines', function(Blueprint $table)
        {
            $table->increments('id');
            $table->float('position', 10, 0)->unsigned();
            $table->string('name', 200)->unique();
            $table->boolean('default')->default(0);
            $table->integer('period')->unsigned();
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
        Schema::dropIfExists('deal_pipelines');
    }
}
