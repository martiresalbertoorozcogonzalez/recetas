<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
            'name' => 'Beto Orozco',
            'email' => 'beto@gmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://alberto-orozco.website',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ]);

         
        DB::table('users')->insert([
            'name' => 'Carlos Emilio',
            'email' => 'carlos@gmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://carlos-emilio.website',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ]);
         
         
         
        DB::table('users')->insert([
            'name' => 'Victor Roberto',
            'email' => 'victor@hotmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://victor.website',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ]);
         


    }
}
