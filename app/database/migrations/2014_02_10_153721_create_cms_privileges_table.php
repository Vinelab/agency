<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsPrivilegesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms_privileges', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('admin_id')->unsigned();
			$table->integer('role_id')->unsigned();
			$table->integer('resource_id')->unsigned();
			$table->string('resource_type');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cms_privileges');
	}

}
