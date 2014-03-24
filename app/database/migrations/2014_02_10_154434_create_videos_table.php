<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("videos", function($table) 
		{
			$table -> increments ("id") -> unsigned();
			$table -> string ("url");
			$table -> string ("title");
			$table -> string ("description");
			$table -> string ("thumbnail");
			$table -> timestamps();
			$table -> softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("videos");
	}

}
