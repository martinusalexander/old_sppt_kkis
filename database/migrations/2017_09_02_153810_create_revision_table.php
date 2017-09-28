<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revision', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('announcement_id')->unsigned();
            $table->integer('revision_no')->unsigned()->default(0);
            $table->string('title');
            $table->string('description');
            $table->datetime('date_time');
            $table->boolean('is_routine')->default(true);
            $table->string('image_path')->nullable();
            $table->boolean('rotating_slide')->default(false);
            $table->string('mass_announcement')->nullable();
            $table->boolean('flyer')->default(false);
            $table->string('bulletin')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->integer('submitter_id')->unsigned()->default(1);        
            $table->timestamps();
            
            $table->unique(array('announcement_id', 'revision_no'));
            
            $table->foreign('announcement_id')->references('id')->on('announcement')->onDelete('cascade');
            $table->foreign('submitter_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revision');
    }
}
