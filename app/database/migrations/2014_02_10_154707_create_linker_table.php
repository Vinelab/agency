<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('linkers', function($table) 
		{
			$table -> increments ('id') -> unsigned();
			$table -> integer ('post_id') -> unsigned();
			$table -> integer ('linkable_id') -> unsigned();
			$table -> string ('linkable_type');
			$table -> foreign ('post_id') -> references('id') -> on('posts') -> onDelete('cascade')->onUpdate('cascade');
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
		Schema::drop('linkers');
	}

}
