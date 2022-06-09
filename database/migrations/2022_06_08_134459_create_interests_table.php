<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interests', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->unsignedInteger('app_setting_id');
            $table->unsignedInteger('detail_id');

            $table->timestamps();

            $table->foreign('detail_id')->references('id')->on('details')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('app_setting_id')->references('id')->on('app_settings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interests');
    }
}
