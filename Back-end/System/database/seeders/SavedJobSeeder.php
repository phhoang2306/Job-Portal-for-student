<?php

namespace Database\Seeders;

use App\Models\SavedJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SavedJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/saved_jobs.csv'), 'r');

        $first_line = true;

        while(($line = fgetcsv($csv)) !== false) {
            if($first_line) {
                $first_line = false;
                continue;
            }

            $saved_job = new SavedJob();

            $saved_job->user_id = $line[0];
            $saved_job->job_id = $line[1];
            $saved_job->created_at = now();
            $saved_job->updated_at = now();

            $saved_job->save();
        }

        fclose($csv);
    }
}
