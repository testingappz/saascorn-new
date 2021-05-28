<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
	  DB::table('users')->truncate(); //for cleaning earlier data to avoid duplicate entries
	  DB::table('users')->insert([
	    'first_name' => 'investor',
	    'last_name' => 'user',
	    'email' => 'investor@gmail.com',
	    'user_type' => 'investor',
	    'password' => Hash::make('Testing@123'),
	  ]);
	  DB::table('users')->insert([
	    'first_name' => 'owner',
	    'last_name' => 'user',
	    'email' => 'owner@gmail.com',
	    'user_type' => 'owner',
	    'password' => Hash::make('Testing@123'),
	  ]);
	}
}
