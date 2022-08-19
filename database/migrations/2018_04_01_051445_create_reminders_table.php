<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('reminder_to');
            $table->integer('reminder_before')->unsigned();
            $table->enum('reminder_before_type', ['minute', 'hour', 'day', 'week']);
            $table->dateTime('reminder_date');
            $table->boolean('is_notified')->default(0);
            $table->boolean('email_notification')->default(0);
            $table->boolean('sms_notification')->default(0);
            $table->text('description', 65535)->nullable();
            $table->integer('linked_id')->unsigned();
            $table->enum('linked_type', ['event']);
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
        Schema::dropIfExists('reminders');
    }
}
