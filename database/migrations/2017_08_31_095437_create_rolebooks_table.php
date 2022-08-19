<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolebooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rolebooks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->integer('linked_id')->unsigned();
            $table->enum('linked_type', ['project']);
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
        Schema::dropIfExists('rolebooks');
    }
}
