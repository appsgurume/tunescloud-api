<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VideosStatusAndCounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {

            $table->smallInteger("status")->default(0)->after("metadata");
            $table->bigInteger("view_count")->default(0)->after("status");
            $table->bigInteger("play_count")->default(0)->after("view_count");
            $table->bigInteger("download_count")->default(0)->after("play_count");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
