<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->float('amount', 10, 0)->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->integer('payment_method_id')->unsigned();
            $table->date('payment_date');            
            $table->text('note', 65535)->nullable();
            $table->string('transaction_id', 200)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
