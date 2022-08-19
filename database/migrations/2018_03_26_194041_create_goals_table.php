<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('goal_owner')->nullable();
            $table->string('name', 200);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('leads_count')->unsigned()->nullable();
            $table->integer('accounts_count')->unsigned()->nullable();
            $table->integer('deals_count')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned();
            $table->float('sales_amount', 10, 0)->unsigned()->nullable();
            $table->text('description', 65535)->nullable();
            $table->enum('access', ['public', 'private'])->default('public');
            $table->boolean('recurring')->default(0);
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
        Schema::dropIfExists('goals');
    }
}
