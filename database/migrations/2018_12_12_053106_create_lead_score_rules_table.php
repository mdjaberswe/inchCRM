<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadScoreRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_score_rules', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('lead_score_id')->unsigned();
            $table->enum('related_to', ['lead_property', 'email_activity']);
            $table->string('attribute', 200);
            $table->string('condition', 200);
            $table->string('value', 200)->nullable();
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
        Schema::dropIfExists('lead_score_rules');
    }
}
