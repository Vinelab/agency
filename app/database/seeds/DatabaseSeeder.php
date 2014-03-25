<?php

use Agency\Cms\Admin;
use Agency\Section;
use Agency\Cms\Authority\Entities\Role;
use Agency\Cms\Authority\Entities\Permission;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('RoleTableSeeder');
		$this->call('AdminTableSeeder');
		$this->call('SectionTableSeeder');
		$this->call('PermissionTableSeeder');

		$this->call('RolePermissionsRelationSeeder');
		$this->call('AdminPrivilegesSeeder');
	}

}

class AdminTableSeeder extends Seeder {

	public function run()
	{
		DB::table('admins')->delete();

		Admin::create([
			'name' => 'Ibrahim Fleifel',
			'email' => 'bob.fleifel@gmail.com',
			'password' => Hash::make('meh')
		]);
	}
}

class SectionTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cms_sections')->delete();

		$sections = [
			[
				'title'      => 'Dashboard',
				'alias'      => 'dashboard',
				'icon'       => 'dashboard',
				'parent_id'  => 0,
				'is_fertile' => true,
				'is_roleable'=> false
			],
			[
				'title'      => 'Content',
				'alias'      => 'content',
				'icon'       => 'rss',
				'parent_id'  => 0,
				'is_fertile' => true,
				'is_roleable'=> true
			],

			[
				'title'      => 'Administration',
				'alias'      => 'administration',
				'icon'       => 'list',
				'parent_id'  => 0,
				'is_fertile' => true,
				'is_roleable'=> true
			],
			[
				'title'      => 'Configuration',
				'alias'      => 'configuration',
				'icon'       => 'cogs',
				'parent_id'  => 0,
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

class PermissionTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cms_permissions')->delete();

		$permissions = [
			[
				'title' => 'create',
				'alias' => 'create',
				'description' => 'Can create new posts.'
			],
			[
				'title' => 'read',
				'alias' => 'read',
				'description' => 'Can only view data.'
			],
			[
				'title' => 'update',
				'alias' => 'update',
				'description' => 'Can modify & update.'
			],
			[
				'title' => 'delete',
				'alias' => 'delete',
				'description' => 'Can permanently delete records.'
			],
			[
				'title' => 'publish',
				'alias' => 'publish',
				'description' => 'Can publish posts directly.'
			],
			[
				'title' => 'Analyze Traffic',
				'alias' => 'analyze-traffic',
				'description' => 'This allows the user to view and analyze traffic...'
			]
		];

		foreach ($permissions as $permission)
		{
			Permission::create($permission);
		}
	}
}

class RoleTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cms_roles')->delete();

		$admin = Role::create([
			'title' => 'Admin',
			'alias' => 'admin'
		]);

		$manager = Role::create([
			'title' => 'Manager',
			'alias' => 'manager'
		]);

		$editor = Role::create([
			'title' => 'Editor',
			'alias' => 'editor'
		]);
	}
}

class RolePermissionsRelationSeeder extends Seeder {

	public function run()
	{
		DB::table('cms_role_permissions')->delete();

		$create_permission  = Permission::where('alias', 'create')->first();
	    $read_permission    = Permission::where('alias', 'read')->first();
	    $update_permission  = Permission::where('alias', 'update')->first();
	    $delete_permission  = Permission::where('alias', 'delete')->first();
	    $publish_permission = Permission::where('alias', 'publish')->first();

	    // define permissions for each of the roles
	    $admin_role = Role::where('alias', 'admin')->first();
	    $manager_role = Role::where('alias', 'manager')->first();
	    $editor_role = Role::where('alias', 'editor')->first();

	    $admin_role->permissions()->attach([
	        $create_permission->id,
	        $read_permission->id,
	        $update_permission->id,
	        $delete_permission->id,
	        $publish_permission->id
	    ]);

	    $manager_role->permissions()->attach([
	        $read_permission->id
	    ]);

	    $editor_role->permissions()->attach([
	        $create_permission->id,
	        $read_permission->id,
	        $update_permission->id,
	        $delete_permission->id
	    ]);
	}
}

class AdminPrivilegesSeeder extends Seeder {


	public function run()
	{
		DB::table('cms_privileges')->delete();

		$admin = Admin::first();
		$sections = Section::get();

		foreach ($sections as $section)
		{
			Authority::authorize($admin)->admin($section);
		}
	}
}