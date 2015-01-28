<?php

use Agency\Cms\Admin;
use Agency\Cms\Section;
use Agency\Cms\Auth\Authorization\Entities\Role;
use Agency\Cms\Auth\Authorization\Entities\Permission;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		NeoEloquent::unguard();

		// Empty all the nodes before seeding
		$connection = (new Role)->getConnection();
        $client = $connection->getClient();

        $batch = $client->startBatch();
		// Remove all relationships and related nodes
		$query = new \Everyman\Neo4j\Cypher\Query($client, 'MATCH (n), (m)-[r]-(c) DELETE n,m,r,c');
        $query->getResultSet();
        // Remove singular nodes with no relations
        $query = new \Everyman\Neo4j\Cypher\Query($client, 'MATCH (n) DELETE n');
        $query->getResultSet();
        $batch->commit();

        $this->call('CmsSectionsSeeder');
		$this->call('RoleAndPermissionsSeeder');
		$this->call('AdminSeeder');
	}

}


class CmsSectionsSeeder extends Seeder {

	public function run()
	{
		$sections = [
			[
				'title'      => 'Dashboard',
				'alias'      => 'dashboard',
				'icon'       => 'dashboard',
				'is_fertile' => true,
				'is_roleable'=> false
			],
			[
				'title'      => 'Content',
				'alias'      => 'content',
				'icon'       => 'rss',
				'is_fertile' => true,
				'is_roleable'=> true
			],
			[
				'title'      => 'Administration',
				'alias'      => 'administration',
				'icon'       => 'list',
				'is_fertile' => true,
				'is_roleable'=> true
			],
			[
				'title'      => 'Configuration',
				'alias'      => 'configuration',
				'icon'       => 'cogs',
				'is_fertile' => true,
				'is_roleable'=> false
			]
		];

		foreach ($sections as $section)
		{
			Section::create($section);
		}
	}
}


class RoleAndPermissionsSeeder extends Seeder {

	public function run()
	{
		// Create permissions so that we relate them while creating the roles.

		$create = Permission::create([
			'title' => 'Create Content',
			'alias' => 'create'
		]);

		$read =	Permission::create([
			'title' => 'Read Content',
			'alias' => 'read'
		]);

		$update = Permission::create([
			'title' => 'Update Content',
			'alias' => 'update'
		]);

		$delete = Permission::create([
			'title' => 'Delete Content',
			'alias' => 'delete'
		]);

		$publish = Permission::create([
			'title' => 'Publish Content',
			'alias' => 'publish'
		]);

		$admin = Role::createWith(['title' => 'Admin', 'alias' => 'admin'], [
			'permissions' => [$create, $read, $update, $delete, $publish]
		]);

		$manager = Role::createWith(['title' => 'Content Manager', 'alias' => 'content-manager'], [
			'permissions' => [$create, $read, $update, $delete, $publish]
		]);

		$editor = Role::createWith(['title' => 'Content Editor', 'alias' => 'content-editor'], [
			'permissions' => [$create, $read, $update, $delete]
		]);

		$publisher = Role::createWith([
			'title' => 'Content Publisher',
			'alias' => 'content-publisher'
		], ['permissions' => [$read, $publish]]);

		$artist_admin = Role::createWith(['title' => 'Admin', 'alias' => 'admin', 'for_artists' => true], [
			'permissions' => [$create, $read, $update, $delete, $publish]
		]);

		$artist_manager = Role::createWith(['title' => 'Content Manager', 'alias' => 'manager', 'for_artists' => true], [
			'permissions' => [$create, $read, $update, $delete, $publish]
		]);

		$artist_editor = Role::createWith(['title' => 'Content Editor', 'alias' => 'editor', 'for_artists' => true], [
			'permissions' => [$create, $read, $update, $delete]
		]);

		$artist_published = Role::createWith(['title' => 'Content Published', 'alias' => 'published', 'for_artists' => true], [
			'permissions' => [$read, $publish]
		]);
	}
}


class AdminSeeder extends Seeder {

	public function run()
	{
		require app_path().'/launch/cms.boot.php';

		// Create Ibrahim Fleifel's admin account
		$ibrahim = Admin::create([
			'name'     => 'Mr. Admin',
			'email'    => 'admin@vinelab.com',
			'password' => Hash::make('meh')
		]);

		// Grant Abed privileges
		$sections = Section::all();

		Auth::authorize($ibrahim)->admin($sections);
	}
}


