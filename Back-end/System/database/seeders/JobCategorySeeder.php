<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/job_category.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $job_category = new JobCategory();

            $job_category->id = $line[0];
            $job_category->job_id = $line[1];
            $job_category->category_id = $line[2];
            $job_category->created_at = now();
            $job_category->updated_at = now();

            $job_category->save();
        }

        fclose($csv);
    }
}
