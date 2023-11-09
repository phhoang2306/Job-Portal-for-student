<?php

namespace Database\Seeders;

use App\Models\EmployerAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployerAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/employer_accounts.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $employer_account = new EmployerAccount();

            $employer_account->id = $line[0];
            $employer_account->username = $line[1];
            $employer_account->password = Hash::make(env('INIT_PASSWORD') . env('PASSWORD_SALT'));
            $employer_account->is_banned = $line[3];
            $employer_account->locked_until = null;
            $employer_account->last_login = null;
            $employer_account->created_at = now();
            $employer_account->updated_at = now();

            $employer_account->save();
        }

        fclose($csv);
    }
}
