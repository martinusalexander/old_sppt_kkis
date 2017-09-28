<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnouncementDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement_distribution', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('announcement_id')->unsigned()->default(1);
            $table->integer('distribution_id')->unsigned()->default(1);
            $table->integer('revision_no')->unsigned()->default(0);
            $table->timestamps();
            
            $table->foreign('announcement_id')->references('id')->on('announcement')->onDelete('cascade');
            $table->foreign('distribution_id')->references('id')->on('distribution')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcement_distribution');
    }
}
