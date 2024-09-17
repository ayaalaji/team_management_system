<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' =>'Admin',
            'last_name' =>'Admin',
            'email' =>'admin@gmail.com',
            'password' =>'12345678',
            'role' =>'admin'
        ]);
    }
}
