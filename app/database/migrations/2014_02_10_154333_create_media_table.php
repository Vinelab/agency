<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medias', function($table) 
		{
			$table -> increments ('id') -> unsigned();
			$table -> integer ('post_id') -> unsigned();
			$table -> integer ('media_id') -> unsigned();
			$table -> string ('media_type');
			$table -> foreign ('post_id') -> references ('id') -> on ('posts') -> onDelete ('cascade')
				-> onUpdate ('cascade');
			$table -> timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medias');
	}

}
