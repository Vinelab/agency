<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsSectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms_sections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('alias');
			$table->string('icon', 20)->default('');
			$table->integer('parent_id')->unsigned();
			$table->boolean('is_fertile')->default(false);
			$table->boolean('is_roleable')->default(false);

			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cms_sections');
	}

}
