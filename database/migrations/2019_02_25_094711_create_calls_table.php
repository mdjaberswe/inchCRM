<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('subject', 200);
            $table->enum('type', ['incoming', 'outgoing'])->default('outgoing');
            $table->integer('client_id')->unsigned();
            $table->enum('client_type', ['lead', 'contact']);
            $table->integer('related_id')->unsigned()->nullable();
            $table->string('related_type')->nullable();
            $table->dateTime('call_time');
            $table->text('description', 65535)->nullable();
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
        Schema::dropIfExists('calls');
    }
}
