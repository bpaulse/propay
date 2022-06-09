<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('people', function (Blueprint $table) {
			$table->increments('id')->unsigned();

			$table->unsignedInteger('user_id');
			$table->smallInteger('state');

			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('people');
	}
}

// INSERT INTO `app_settings`(`description`, `abbr`, `Name`) VALUES ('Language', 'MAN', 'Mandarin Chinese'),('Language', 'SPA', 'Spanish'),('Language', 'ENG', 'English'),('Language', 'ARA', 'Arabic'),('Language', 'HIN', 'Hindi'),('Language', 'BEN', 'Bengali'),('Language', 'POR', 'Portuguese'),('Language', 'RUS', 'Russian'),('Language', 'JAP', 'Japanese'),('Language', 'WPU', 'Western Punjabi'),('Language', 'JAV', 'Javanese'),('Language', 'AFR', 'Afrikaans'),('Language', 'NDE', 'isiNdebele'),('Language', 'XHO', 'isiXhosa'),('Language', 'ZUL', 'isiZulu'),('Language', 'SES', 'Sesotho'),('Language', 'SSL', 'Sesotho sa Leboa'),('Language', 'SET', 'Setswana'),('Language', 'SWA', 'siSwati'),('Language', 'TSH', 'Tshivenda'),('Language', 'XIT', 'Xitsonga');

// INSERT INTO `app_settings`(`description`, `abbr`, `Name`) VALUES ('Interests', 'Hik', 'Hiking'),('Interests', 'Bac', 'Backpacking'),('Interests', 'Cam', 'Camping'),('Interests', 'Hun', 'Hunting'),('Interests', 'Fis', 'Fishing'),('Interests', 'Arc', 'Archery'),('Interests', 'Can', 'Canoeing'),('Interests', 'Kay', 'Kayaking'),('Interests', 'Run', 'Running');
