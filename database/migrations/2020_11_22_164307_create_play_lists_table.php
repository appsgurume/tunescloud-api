<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_lists', function (Blueprint $table) {
            $table->id();

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
        Schema::dropIfExists('play_lists');
    }
}
