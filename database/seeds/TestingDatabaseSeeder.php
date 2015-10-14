<?php

use AblaFahita\User;
use Illuminate\Database\Seeder;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class TestingDatabaseSeeder extends Seeder {

    public function run()
    {
        // NeoEloquent::unguard();

        $this->call('TestingUserSeeder');
    }
}

class TestingUserSeeder extends Seeder {

    public function run()
    {
        User::create([
            'name'    => 'Mr. Testing',
            'email'   => 'mr@testing.net',
            'avatar'  => 'http://some.picture.here',
            'blocked' => false,
        ]);
    }
}
