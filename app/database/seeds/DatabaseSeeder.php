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
       	$this->call('XfactorSeeder');
		$this->call('RoleAndPermissionsSeeder');
		$this->call('AdminSeeder');
		$this->call('DataSeeder');
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

class XfactorSeeder extends Seeder {

	public function run()
	{
		$sections = [
			// [
			// 	'title'      => 'Users',
			// 	'alias'      => 'users',
			// 	'icon'       => 'group',
			// 	'is_fertile' => true,
			// 	'is_roleable'=> true
			// ],
			[
				'title'      => 'Teams',
				'alias'      => 'teams',
				'icon'       => 'group',
				'is_fertile' => true,
				'is_roleable'=> true
			],

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


class DataSeeder extends Seeder {

	public function run()
	{
		$teams = [
			[
				'title'=>'اليسا',
				'slug' => 'اليسا',
				'score' => 0,
				'user_count' => 0,
				'photo'=>[
					'original'=>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.jpeg',
					'thumbnail' =>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.thumb.jpeg',
					'square' => 'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.square.jpeg',
					'small'=>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.small.jpeg'
				]
			],
			[
				'title'=>'راغب علامة',
				'slug' => 'راغب-علامة',
				'score' => 0,
				'user_count' => 0,
				'photo'=>[
					'original'=>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.jpeg',
					'thumbnail' =>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.thumb.jpeg',
					'square' => 'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.sq.jpeg',
					'small'=>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.small.jpeg'
				]
			],
			[
				'title'=>'دنيا',
				'slug' => 'دنيا',
				'score' => 0,
				'user_count' => 0,
				'photo'=>[
					'original'=>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.jpeg',
					'thumbnail' =>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.thumb.jpeg',
					'square' => 'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.sq.jpeg',
					'small'=>'https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.small.jpeg'
				]
			],
		];

		$score_service= App::make('Xfactor\Contracts\Services\ScoreServiceInterface');


		foreach ($teams as $team) {

			$team = Xfactor\Team::createWith(	[
											'title'=>$team['title'],
											'slug'=>$team['slug'],
											'score'=>$team['score'],
											'user_count'=>$team['user_count']
										],
										['image' => $team['photo']
										]);

			$score_service->createTeamScore($team->slug);
			
		}

		$elissa_users = [
			[
				"name"=> "ibrahim fleifel",
	            "gigya_id"=> "_guid_OgpsAoIgWZY6SCHjZiLg8iukw6BMKEYh5Q0YRdQCzjU",
	            "avatar"=>"https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-xpa1/v/t1.0-9/10857827_10152439008631993_3007437159977895539_n.jpg?oh=5979ca7f5b6bd9107e733a1b67a5d2c9&oe=5554A8EA&__gda__=1432055036_d702a7b58077e850ee2d3732e0b3c594",
	            "country"=>"lebanon"
			],
			[
				"name"=> "Rasha Kassab",
                "gigya_id"=>"_guid_gigya_id_5",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfp1/v/t1.0-1/p160x160/10411204_959528017272_5614593003384879480_n.jpg?oh=c2e1dd9f96635476ef0dfd6afe6eab09&oe=556128CF&__gda__=1432501978_a301cbcab7339047d7020cb6dc9068ff",
                "country"=>"lebanon"
			],
			[
				"name"=> "Tony Ghazal",
                "gigya_id"=>"_guid_gigya_id_3",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p160x160/1392062_10153417692275464_640775601_n.jpg?oh=564ef7a0f6aed6e666bb31bd09750837&oe=55567DF1&__gda__=1431808437_a79aacfeba2aacf6117a45d25992d84d",
                "country"=>"lebanon"
			],
			[
				"name"=> "Mirella Khoury",
                "gigya_id"=>"_guid_gigya_id_4",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/10955683_10203089895398834_1928959484361580686_n.jpg?oh=615d91b9178834317bb40fcd478faa5c&oe=554C8ACB&__gda__=1430885811_faee5dc3801e865fdf25e360e151e742",
                "country"=>"lebanon"
			],
			[
				"name"=> "Lana Sabbdine",
                "gigya_id"=>"_guid_gigya_id_330",
                "avatar"=>" https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p160x160/10922441_10155349734785105_7294652769952253701_n.jpg?oh=81d32a440459f2c56c605eb2cbb1df8d&oe=5557439D&__gda__=1435872524_219fbe4be65d6ab46e3d9a931b6fd6e2",
                "country"=>"lebanon"
			],
			[
				"name"=> "Youssef Manasfi",
	            "gigya_id"=>"_guid_gigya_id_550",
	            "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash2/v/t1.0-1/c0.0.160.160/p160x160/998421_10153083157980174_1205953339_n.jpg?oh=1694511d6b8629e03fe8365e9b02d6c7&oe=555441E8&__gda__=1430818449_4976dea9c33baaa95287c30ff46a2028",
	            "country"=>"lebanon"
			],
			[
				"name"=> "Farah Assi",
                "gigya_id"=>"_guid_gigya_id_6",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p160x160/10947189_10155370223870508_1448050854421059805_n.jpg?oh=65d1a4c216a969ce4371ac8eee036c25&oe=558FAC0D&__gda__=1430902232_62e2fedd17053fc584d16f3583cd2996",
                "country"=>"lebanon"
			],
			[
				"name"=> "Hanin Haidar",
                "gigya_id"=>"_guid_gigya_id_7",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/1236827_10154691429795565_3439119293157375266_n.jpg?oh=289ef8bcad84cea55b8802f79de0a3f3&oe=5560892D&__gda__=1432442945_1b56d01eab2f22ec6eaaeab98b678132",
                "country"=>"lebanon"
			],
			[
			 	"name"=> "Naima Bawab",
                "gigya_id"=>"_guid_gigya_id_8",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/c0.0.160.160/p160x160/10299955_10203196745340706_1987348888600877850_n.jpg?oh=0faae57d8d00dd709b6aa4dee321e960&oe=554A0DBB&__gda__=1435425029_6e81877ef9951bc06268c6d24b9036fa",
                "country"=>"lebanon"
			],
			[
				"name"=> "Rein Dagher",
                "gigya_id"=>"_guid_gigya_id_9",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p160x160/10373642_1393109577664855_8455989094913645858_n.jpg?oh=a9795b79221f8a129b704ca872212d99&oe=5555E052&__gda__=1431382352_c570fc724ed982f85b451ee6f776795b",
                "country"=>"lebanon"
			],
			[
				"name"=> "Riham Chamas",
                "gigya_id"=>"_guid_gigya_id_10",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/10421358_778093958924373_4177680978919967605_n.jpg?oh=1dbe8f6c6ef88ef1fff8b512a887b95f&oe=555CB2F3&__gda__=1432214805_214328270c372ef0abb8d0875c8bafaf",
                "country"=>"lebanon"
			]

		];

		$user_repo= App::make('Xfactor\Contracts\Repositories\UserRepositoryInterface');
		$score_repo = App::make('Xfactor\Contracts\Repositories\ScoreRepositoryInterface');
		$team_repo = App::make('Xfactor\Contracts\Repositories\TeamRepositoryInterface');

		foreach ($elissa_users as $user) {

			$relations['score'] = $score_repo->create(0,0,0,0);
			$user = $user_repo->createWith(	$user['name'],
											$user['gigya_id'],
											$user['avatar'],
											$user['country'],
											$relations
											);

			$team_repo->join('اليسا',$user);

			$score_service->createScore($user->gigyaId(), 'اليسا');
			
		}


		$ragheb_users = [

			[
				"name"=> "Lama Kronfol",
                "gigya_id"=>"_guid_gigya_id_12",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/10942607_1380800592232091_8630114142539991660_n.jpg?oh=e779f9ca35b2c38fb73623c9fc10746b&oe=559029A4&__gda__=1432743624_c0260bdb6bb8ee532098d7e663dce8c9",
                "country"=>"lebanon"
			],
			[
				"name"=> "Tarek Manasfi",
                "gigya_id"=>"_guid_gigya_id_13",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfp1/v/t1.0-1/c12.0.160.160/p160x160/14226_10154538143720403_3933227280658933265_n.jpg?oh=239bcbfd0aee0f419389470eb4bb7c3e&oe=5554CD1C&__gda__=1435779852_8d4c8f19c633a3f0946c6b5290438e32",
                "country"=>"lebanon"
			],
			[
				"name"=> "Tony Nemer",
                "gigya_id"=>"_guid_gigya_id_14",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/10369121_1042754739074751_7576901906495244921_n.jpg?oh=c2d9a959f920b262952c229d08361722&oe=5551E260&__gda__=1431222540_2cbeb2d393c8359b5ef6a938935f54ab",
                "country"=>"lebanon"
			],
			[
			 	"name"=> "Georges Bou Mansour",
                "gigya_id"=>"_guid_gigya_id_15",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/10943660_10155135696760344_7666281420216747101_n.jpg?oh=f60ac9b96ae9f09e099383558b2f25ca&oe=5596D7AA&__gda__=1430982098_3051d1b408aea84763ca53105e0e0e5c",
                "country"=>"lebanon"
			],
			[
				"name"=> "Christell Abi Nassif",
                "gigya_id"=>"_guid_gigya_id_150",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p160x160/10407403_10153016985485993_1642674098881930046_n.jpg?oh=836b3e7981ae51c1a1975596094bdb93&oe=554B5865&__gda__=1432483828_9371e8a041a87ef335e6b0e000bae5f2",
                "country"=>"lebanon"
			],
			[
			 	"name"=> "Mohamad Manasfi",
                "gigya_id"=>"_guid_gigya_id_16",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-prn2/v/t1.0-1/p160x160/533640_10151162242890793_1567356209_n.jpg?oh=79ee56cafabd6407b2e5697fad88841c&oe=5553F5D2&__gda__=1432723156_aa8dc5a8e999597b028bec48b6a08d95",
                "country"=>"lebanon"
			],
			[
				"name"=> "Hussein Huballah",
                "gigya_id"=>"_guid_gigya_id_17",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p160x160/1392062_10153417692275464_640775601_n.jpg?oh=564ef7a0f6aed6e666bb31bd09750837&oe=55567DF1&__gda__=1431808437_a79aacfeba2aacf6117a45d25992d84d",
                "country"=>"lebanon"
			], 
			[
				"name"=> "Jina Abbas",
                "gigya_id"=>"_guid_gigya_id_18",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/c0.0.160.160/p160x160/1908483_870231373021826_5348545183043848094_n.jpg?oh=1c9d2f39683775951117cef2d6ef181c&oe=554F57B7&__gda__=1431974900_d0bf5ff63b3fd3121384291e1a70bf91",
                "country"=>"lebanon",
			],
			[
				"name"=> "Chadi Fakher Eldine",
                "gigya_id"=>"_guid_gigya_id_19",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p160x160/10628492_1519129105020943_8309643872506678734_n.jpg?oh=41da82841a65bf00c062adf67fc42c8a&oe=5591298B&__gda__=1432785801_4cb3eefc6739f0ca14f1179679f5a695",
                "country"=>"lebanon"
			],
			[
				"name"=> "Marwa Daher",
                "gigya_id"=>"_guid_gigya_id_20",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p160x160/10987004_823870041010072_1762827741120621131_n.jpg?oh=1ad5b42edcddd8e93c62c3def55b24b6&oe=55637225&__gda__=1432761632_b940fe818fcbd2dbd37eacc139bbec71",
                "country"=>"lebanon"
			]

		];

		foreach ($ragheb_users as $user) {

			$relations['score'] = $score_repo->create(0,0,0,0);
			$user = $user_repo->createWith(	$user['name'],
											$user['gigya_id'],
											$user['avatar'],
											$user['country'],
											$relations
											);

			$team_repo->join('راغب-علامة',$user);
			$score_service->createScore($user->gigyaId(), 'راغب-علامة');

			
		}

		$donia_users = [
			[
				"name"=> "Rita Nemer",
                "gigya_id"=>"_guid_gigya_id_21",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/p160x160/10952104_10204979704164618_192221368031652331_n.jpg?oh=ef5554487bc29264a877d452a121ab8f&oe=55510FC4&__gda__=1431880719_08603d03f0700d37bcc97771faba6e30",
                "country"=>"lebanon"
			],
			[
				"name"=> "Nada Abi Antoun",
                "gigya_id"=>"_guid_gigya_id_22",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p160x160/10885252_10155202134060531_7629897452292289933_n.jpg?oh=a0a889ea7abc20e19c6740d88cb15396&oe=554D61FD&__gda__=1432158248_c2afe8e85d09d6b148c5f53facb02c5d",
                "country"=>"lebanon"
			],
			[
				"name"=> "Mohamad Makieh",
                "gigya_id"=>"_guid_gigya_id_23",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/v/t1.0-1/c64.41.513.513/s160x160/1009849_10152115704702564_36402166_n.jpg?oh=b81398a5b9de7d895d8eadf5ec93fbcf&oe=558F62B7&__gda__=1431782726_9f0adf7b8a2e36d2f04625fa4872249d",
                "country"=>"lebanon"
			],
			[
				"name"=> "Abed Safadi",
                "gigya_id"=>"_guid_gigya_id_24",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p160x160/10868261_804566872933368_3921898518467502196_n.jpg?oh=462696b6afd054367d303dde5c1f4826&oe=555253EC&__gda__=1432702986_c5122c6ad4807a35d490fc357e166df4",
                "country"=>"lebanon"
			],
			[
				"name"=> "Mustasfa furtunato",
                "gigya_id"=>"_guid_gigya_id_25",
                "avatar"=>" https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p160x160/10931086_766557746768068_7342457461607943547_n.jpg?oh=3972ca0162cff4cf71949398a6c71144&oe=554D114E&__gda__=1435779659_45c6fabee877db6ab35a08ffe7f6d2e4",
                "country"=>"lebanon"
			],
			[
				"name"=> "Mohammad Hassan Akil",
                "gigya_id"=>"_guid_gigya_id_26",
                "avatar"=>"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/p160x160/10570393_816398995066363_6751958165282194761_n.jpg?oh=3bb934892cb5268e57ba0269ad10c598&oe=5559390D&__gda__=1432189873_74e2e446bb2f00d20889522485957373",
                "country"=>"lebanon"
			]
		];

		foreach ($donia_users as $user) {

			$relations['score'] = $score_repo->create(0,0,0,0);
			$user = $user_repo->createWith(	$user['name'],
											$user['gigya_id'],
											$user['avatar'],
											$user['country'],
											$relations
											);

			$team_repo->join('دنيا',$user);
			$score_service->createScore($user->gigyaId(), 'دنيا');

			
		}

		
	}
}





