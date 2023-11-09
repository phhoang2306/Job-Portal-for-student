<?php

namespace Database\Seeders;

use App\Models\UserEducation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/user_educations.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $user_education = new UserEducation();

            $user_education->id = $line[0];
            $user_education->user_id = $line[1];
            $user_education->university = $line[2];
            $user_education->major = $line[3];
            $user_education->start = $line[4];
            $user_education->end = $line[5];
            $user_education->created_at = now();
            $user_education->updated_at = now();

            $user_education->save();
        }

        fclose($csv);
    }
}
