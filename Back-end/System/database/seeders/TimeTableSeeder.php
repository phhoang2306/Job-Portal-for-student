<?php

namespace Database\Seeders;

use App\Models\TimeTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/time_tables.csv'), 'r');

        $first_line = true;

        while(($line = fgetcsv($csv)) !== false) {
            if($first_line) {
                $first_line = false;
                continue;
            }

            $time_table = new TimeTable();

            $time_table->id = $line[0];
            $time_table->user_id = $line[1];
            $time_table->coordinate = $line[2];
            $time_table->created_at = now();
            $time_table->updated_at = now();

            $time_table->save();
        }

        fclose($csv);
    }
}
