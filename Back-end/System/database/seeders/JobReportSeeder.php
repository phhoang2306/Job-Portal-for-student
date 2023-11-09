<?php

namespace Database\Seeders;

use App\Models\JobReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/job_reports.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $job_report = new JobReport();

            $job_report->id = $line[0];
            $job_report->job_id = $line[1];
            $job_report->user_id = $line[2];
            $job_report->reason = $line[3];
            $job_report->created_at = now();
            $job_report->updated_at = now();

            $job_report->save();
        }

        fclose($csv);
    }
}
