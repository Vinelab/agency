<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function($table) 
		{
			$table->increments ('id')->unsigned();
			$table->string('title');
			$table->text('body');
			$table->boolean("published")->default(0);
			$table->integer('admin_id')->unsigned();
			$table->integer('section_id')->unsigned();
			$table -> foreign ('admin_id') -> references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
			$table -> foreign ('section_id') -> references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
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
		Schema::drop('posts');
	}

}
