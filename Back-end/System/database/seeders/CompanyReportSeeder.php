<?php

namespace Database\Seeders;

use App\Models\CompanyReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/company_reports.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $company_report = new CompanyReport();

            $company_report->id = $line[0];
            $company_report->company_id = $line[1];
            $company_report->user_id = $line[2];
            $company_report->reason = $line[3];
            $company_report->created_at = now();
            $company_report->updated_at = now();

            $company_report->save();
        }

        fclose($csv);
    }
}
