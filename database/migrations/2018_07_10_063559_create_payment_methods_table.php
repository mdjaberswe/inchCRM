<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function(Blueprint $table)
        {
            $table->increments('id');
            $table->float('position', 10, 0)->unsigned();
            $table->string('name', 200)->unique();
            $table->text('description', 65535)->nullable();
            $table->boolean('status');
            $table->boolean('masked')->default(0);
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
        Schema::dropIfExists('payment_methods');
    }
}
