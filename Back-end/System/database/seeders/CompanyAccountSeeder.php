<?php

namespace Database\Seeders;

use App\Models\CompanyAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanyAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/company_accounts.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $company_account = new CompanyAccount();

            $company_account->id = $line[0];
            $company_account->username = $line[1];
            $company_account->password = Hash::make(env('INIT_PASSWORD') . env('PASSWORD_SALT'));
            $company_account->is_verified = 1;
            $company_account->is_banned = $line[4];
            $company_account->locked_until = null;
            $company_account->last_login = null;
            $company_account->created_at = now();
            $company_account->updated_at = now();

            $company_account->save();
        }

        fclose($csv);
    }
}
