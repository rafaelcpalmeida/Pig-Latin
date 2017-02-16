<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'rafaelcpalmeida',
            'email' => 'rafaelcpalmeida@gmail.com',
            'password' => Hash::make('secret'),
            'confirmation_token' => 'lJ1ScT8C9uoumr8vpsiCU1zO2rt5qzK2',
            'api_token' => 'tqqM0QU7BwIpjjXkgWHl4HqLWCDb3hsMJ0vV26kj3MYjF6XjU7',
            'confirmed' => true
        ]);
    }
}
