<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishDateAndPublishStateToPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->dateTime('publish_date')->default(date('Y-m-d H:i:s'));
			$table->enum('publish_state',['editing', 'published', 'scheduled'])->default('editing');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function(Blueprint $table)
		{

			$table->dropColumn('publish_date','publish_state');
		});
	}

}
