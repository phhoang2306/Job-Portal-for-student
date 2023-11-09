<?php

namespace Database\Seeders;

use App\Models\JobSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/job_skills.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $job_skill = new JobSkill();

            $job_skill->id = $line[0];
            $job_skill->job_id = $line[1];
            $job_skill->skill = $line[2];
            $job_skill->created_at = now();
            $job_skill->updated_at = now();

            $job_skill->save();
        }

        fclose($csv);
    }
}
