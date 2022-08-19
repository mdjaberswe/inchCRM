<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_members', function(Blueprint $table)
        {
            $table->integer('campaign_id');
            $table->integer('member_id');
            $table->enum('member_type', ['lead', 'contact']);
            $table->string('status', 200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_members');
    }
}
