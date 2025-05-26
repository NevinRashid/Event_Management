<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin = User::firstOrCreate([
            'username'      => 'nevin123',
            'first_name'    => 'Nevin',
            'last_name'     => 'Rashid',
            'email'         => 'nevin@gmail.com',
            'password'      => bcrypt('Nevin#123pass'),  
            'phone_number'  => '0000000000',
        ]);
        $super_admin->assignRole('super admin');  

        $super_admin = User::firstOrCreate([
            'username'      => 'mohamad123',
            'first_name'    => 'Mohamad',
            'last_name'     => 'Rashid',
            'email'         => 'mohamad@gmail.com',
            'password'      => bcrypt('Mohamad#123pass'),  
            'phone_number'  => '0000000000',
        ]);
        $super_admin->assignRole('admin');  

    }
}
