<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->integer('contact_id')->unsigned()->nullable();
            $table->integer('deal_owner')->unsigned();   
            $table->string('name', 200);
            $table->integer('currency_id')->unsigned();
            $table->float('amount', 10, 0)->unsigned()->default(0);
            $table->tinyInteger('probability')->unsigned();
            $table->date('closing_date')->nullable();
            $table->integer('deal_pipeline_id')->unsigned();
            $table->integer('deal_stage_id')->unsigned();
            $table->integer('deal_type_id')->unsigned()->nullable();
            $table->integer('source_id')->unsigned()->nullable();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->boolean('recurring')->default(0);
            $table->text('description', 65535)->nullable();
            $table->enum('access', ['private', 'public', 'public_rwd'])->default('public');
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
        Schema::dropIfExists('deals');
    }
}
