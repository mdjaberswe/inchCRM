<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_contacts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('contact_id')->unsigned();
            $table->integer('linked_id')->unsigned();
            $table->enum('linked_type', ['deal', 'project']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant_contacts');
    }
}
