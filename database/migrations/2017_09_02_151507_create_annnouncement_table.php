<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnnouncementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('current_revision_no')->unsigned()->default(0);
            $table->text('title');
            $table->text('description');
            $table->datetime('date_time');
            $table->boolean('is_routine')->default(true);
            $table->string('image_path')->nullable();
            $table->boolean('rotating_slide')->default(false);
            $table->text('mass_announcement')->nullable();
            $table->boolean('flyer')->default(false);
            $table->text('bulletin')->nullable();
            $table->text('website')->nullable();
            $table->text('facebook')->nullable();
            $table->text('instagram')->nullable();
            $table->integer('creator_id')->unsigned()->default(1);
            $table->integer('approver_id')->unsigned()->nullable();
            $table->boolean('is_approved')->default(false);
            $table->integer('last_editor_id')->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('creator_id')->references('id')->on('user');
            $table->foreign('approver_id')->references('id')->on('user');
            $table->foreign('last_editor_id')->references('id')->on('user');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcement');
    }
}
