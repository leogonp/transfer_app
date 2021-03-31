<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	for ($i=0; $i < 50; $i++) { 
	        DB::table('people')->insert([
	            'name' => Str::random(10),
	            'email' => Str::random(10).'@gmail.com',
	            'password' => Hash::make('password'),
	            'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
	            'shopkeeper' => (bool)random_int(0, 1),
	            'created_at' => date("Y-m-d H:i:s"),
	            'updated_at' => date("Y-m-d H:i:s"),
	            'wallet' => rand(0,1000)
	        ]);
	    }
    }
}
