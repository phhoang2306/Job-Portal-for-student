<?php

namespace Database\Seeders;

use App\Models\UserSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/user_skills.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $user_skill = new UserSkill();

            $user_skill->id = $line[0];
            $user_skill->user_id = $line[1];
            $user_skill->skill = $line[2];
            $user_skill->created_at = now();
            $user_skill->updated_at = now();

            $user_skill->save();
        }

        fclose($csv);
    }
}
