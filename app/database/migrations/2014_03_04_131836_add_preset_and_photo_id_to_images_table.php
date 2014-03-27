<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPresetAndPhotoIdToImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("images", function($table)
		{
			$table->enum('preset',['original', 'small', 'thumbnail', 'square'])->default('original');
			$table->string('photo_id')->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('images', function(Blueprint $table){
			$table->dropColumn('preset','photo_id');
		});
	}

}
