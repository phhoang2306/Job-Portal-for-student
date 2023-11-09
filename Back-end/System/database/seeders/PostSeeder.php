<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/posts.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $post = new Post();

            $post->id = $line[0];
            $post->user_id = $line[1];
            $post->cv_id = $line[2];
            $post->title = $line[3];
            $post->content = $line[4];
            $post->created_at = now();
            $post->updated_at = now();

            $post->save();
        }

        fclose($csv);
    }
}
