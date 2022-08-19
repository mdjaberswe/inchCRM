<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_cases', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('case_name', 200)->unique();
            $table->string('case_display_name', 200);
            $table->text('message_format', 65535)->nullable();
            $table->boolean('web_notification')->default(1);
            $table->boolean('email_notification')->default(1);
            $table->boolean('sms_notification')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_cases');
    }
}
