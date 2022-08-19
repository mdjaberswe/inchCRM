<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePipelineStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_stages', function(Blueprint $table)
        {
            $table->integer('deal_pipeline_id');
            $table->integer('deal_stage_id');
            $table->boolean('forecast')->default(1)->nullable();
            $table->float('position', 10, 0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipeline_stages');
    }
}
