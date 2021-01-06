<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_list_videos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('play_list_id');
            $table->unsignedBigInteger('video_id');
            $table->boolean('is_deleted')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('play_list_id')->references('id')->on('play_lists');
            $table->foreign('video_id')->references('id')->on('videos');

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
        Schema::dropIfExists('play_list_videos');
    }
}
