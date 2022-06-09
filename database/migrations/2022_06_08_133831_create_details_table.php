<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('details', function (Blueprint $table) {
			$table->increments('id')->unsigned();

			$table->string('Name');
			$table->string('Surname');
			$table->string('Mobile', 12);
			$table->string('Email');
			$table->date('DateOfBirth');
			$table->string('Idnumber', 13);
			$table->unsignedInteger('person_id');

			$table->timestamps();
			
			$table->foreign('person_id')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
			// $table->foreign('person_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('details');
	}
}
