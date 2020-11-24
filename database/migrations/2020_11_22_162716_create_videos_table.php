<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('original_url', 1024)->nullable();
            $table->string('audio_url', 1024)->nullable();
            $table->string('transcoded_audio_url', 1024)->nullable();
            $table->string('thumbnail', 1024)->nullable();
            $table->string('cover', 1024)->nullable();
            $table->string('title', 512)->nullable();
            $table->string('description', 1024)->nullable();
            $table->string('hashtags', 1024)->nullable();
            $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('videos');
    }
}
