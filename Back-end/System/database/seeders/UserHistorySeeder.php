<?php

namespace Database\Seeders;

use App\Models\UserHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/user_history.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $userHistory = new UserHistory();

            $userHistory->id = $line[0];
            $userHistory->user_id = $line[1];
            $userHistory->job_id = $line[2];
            $userHistory->times = $line[3];
            $userHistory->created_at = now();
            $userHistory->updated_at = now();

            $userHistory->save();
        }

        fclose($csv);
    }
}
