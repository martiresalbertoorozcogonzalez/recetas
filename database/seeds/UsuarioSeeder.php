<?php

use App\User;
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
 
        $user = User::create([
            'name' => 'Beto Orozco',
            'email' => 'beto@gmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://alberto-orozco.website',
        ]);
        $user->perfil()->create();
        
        $user2 = User::create([
            'name' => 'Carlos Orozco',
            'email' => 'carlos@gmail.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://carlos-orozco.website',
        ]);
        $user->perfil()->create();

        
    }
}
