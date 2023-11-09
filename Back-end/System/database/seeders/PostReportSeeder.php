<?php

namespace Database\Seeders;

use App\Models\PostReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/post_reports.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $postReport = new PostReport();

            $postReport->id = $line[0];
            $postReport->post_id = $line[1];
            $postReport->user_id = $line[2];
            $postReport->reason = $line[3];
            $postReport->created_at = now();
            $postReport->updated_at = now();

            $postReport->save();
        }

        fclose($csv);
    }
}
