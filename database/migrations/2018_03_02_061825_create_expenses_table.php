<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('expense_category_id')->unsigned();
            $table->string('name', 200)->nullable();
            $table->float('amount', 10, 0)->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->date('expense_date');
            $table->boolean('billable')->default(0);
            $table->boolean('recurring')->default(0);
            $table->integer('account_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('converted_invoice_id')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
