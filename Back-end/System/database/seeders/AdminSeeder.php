<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    protected $connection = 'mysql';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('admin_accounts')->insert([
            'username' => 'Admin',
            'password' => Hash::make(env('INIT_PASSWORD') . env('PASSWORD_SALT')),
            'full_name' => 'Admin',
            'avatar' => 'https://i.imgur.com/1Z1Z1Z1.png',
            'is_banned' => false,
            'locked_until' => null,
            'last_login' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
