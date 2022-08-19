<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('linked_id')->unsigned(); 
            $table->enum('linked_type', ['staff', 'account', 'contact', 'lead']);        
            $table->string('media', 200); // 'facebook', 'twitter', 'googleplus', 'instagram', 'youtube', 'pinterest', 'tumblr', 'linkedin', 'skype', 'github', 'snapchat', 'twitch', 'line', 'wechat', 'kik', 'ask.fm', 'soundcloud', 'spotify', 'qq', 'kakaotalk', 'vk', 'ok', 'bbm'
            $table->text('data', 65535);
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
        Schema::dropIfExists('social_media');
    }
}
