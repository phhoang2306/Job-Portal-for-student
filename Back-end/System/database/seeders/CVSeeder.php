<?php

namespace Database\Seeders;

use App\Models\CV;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/CV.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $CV = new CV();

            $CV->id = $line[0];
            $CV->user_id = $line[1];
            $CV->cv_name = $line[2];
            $CV->cv_path = $line[3];
            $CV->cv_note = $line[4];
            $CV->created_at = now();
            $CV->updated_at = now();

            $CV->save();
        }

        fclose($csv);
    }
}
