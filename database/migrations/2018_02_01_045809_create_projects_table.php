<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->integer('project_owner')->unsigned();
            $table->integer('deal_id')->unsigned()->nullable();
            $table->string('name', 200);
            $table->text('description', 65535)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['upcoming', 'in_progress', 'completed', 'cancelled']);
            $table->tinyInteger('completion_percentage')->unsigned()->default(0);
            $table->enum('access', ['public', 'private']);
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
        Schema::dropIfExists('projects');
    }
}
