<?php

namespace Database\Seeders;

use App\Models\EmployerProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/employer_profiles.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $employer_profile = new EmployerProfile();

            $employer_profile->id = $line[0];
            $employer_profile->company_id = $line[1];
            $employer_profile->full_name = $line[2];
            $employer_profile->created_at = now();
            $employer_profile->updated_at = now();

            $employer_profile->save();
        }

        fclose($csv);
    }
}
