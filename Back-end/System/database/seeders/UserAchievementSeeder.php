<?php

namespace Database\Seeders;

use App\Models\UserAchievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/user_achievements.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $user_achievement = new UserAchievement();

            $user_achievement->id = $line[0];
            $user_achievement->user_id = $line[1];
            $user_achievement->description = $line[2];
            $user_achievement->created_at = now();
            $user_achievement->updated_at = now();

            $user_achievement->save();
        }

        fclose($csv);
    }
}
