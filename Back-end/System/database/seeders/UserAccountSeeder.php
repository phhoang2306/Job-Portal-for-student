<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/user_accounts.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $user_account = new UserAccount();

            $user_account->id = $line[0];
            $user_account->username = $line[1];
            $user_account->password = Hash::make(env('INIT_PASSWORD') . env('PASSWORD_SALT'));
            $user_account->is_banned = $line[3];
            $user_account->locked_until = null;
            $user_account->last_login = null;
            $user_account->created_at = now();
            $user_account->updated_at = now();

            $user_account->save();
        }

        fclose($csv);
    }
}
