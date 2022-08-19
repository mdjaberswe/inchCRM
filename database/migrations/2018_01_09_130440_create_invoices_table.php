<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id')->unsigned();  
            $table->integer('contact_id')->unsigned()->nullable();
            $table->integer('deal_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();     
            $table->integer('sale_agent')->unsigned();            
            $table->integer('number')->unique();      
            $table->string('reference', 200)->nullable();
            $table->string('subject', 200)->nullable();
            $table->enum('status', ['draft', 'paid', 'partially_paid', 'unpaid']);
            $table->date('invoice_date');
            $table->date('date_pay_before')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->enum('discount_type', ['pre', 'post', 'flat']);
            $table->float('sub_total', 10, 0)->unsigned();
            $table->float('total_tax', 10, 0)->unsigned();
            $table->float('total_discount', 10, 0)->unsigned();
            $table->float('adjustment', 10, 0);
            $table->float('grand_total', 10, 0);
            $table->float('payment', 10, 0)->unsigned()->default(0);
            $table->boolean('recurring')->default(0);
            $table->text('term_condition', 65535)->nullable();
            $table->text('note', 65535)->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
