<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function(Blueprint $table)
        {
            $table->increments('id');            
            $table->integer('campaign_owner')->unsigned();
            $table->integer('campaign_type')->unsigned()->nullable();
            $table->string('name', 200);
            $table->text('description', 65535)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['planning', 'active', 'inactive', 'completed'])->nullable();
            $table->integer('currency_id')->unsigned();
            $table->float('expected_revenue', 10, 0)->unsigned()->default(0);
            $table->float('budgeted_cost', 10, 0)->unsigned()->default(0);
            $table->float('actual_cost', 10, 0)->unsigned()->default(0);
            $table->integer('numbers_sent')->unsigned()->default(0);
            $table->tinyInteger('expected_response')->unsigned()->default(0);
            $table->enum('access', ['public', 'private'])->default('public');
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
        Schema::dropIfExists('campaigns');
    }
}
