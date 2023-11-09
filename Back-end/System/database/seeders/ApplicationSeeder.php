<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/applications.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $application = new Application();

            $application->id = $line[0];
            $application->job_id = $line[1];
            $application->user_id = $line[2];
            $application->cv_id = $line[3];
            $application->status = $line[4];
            $application->created_at = now();
            $application->updated_at = now();

            $application->save();
        }

        fclose($csv);
    }
}
