<?php

namespace Database\Seeders;

use App\Models\PostComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeding_data/post_comments.csv'), 'r');

        $first_line = true;

        while (($line = fgetcsv($csv)) !== false) {
            if ($first_line) {
                $first_line = false;
                continue;
            }

            $postComment = new PostComment();

            $postComment->id = $line[0];
            $postComment->post_id = $line[1];
            $postComment->user_id = $line[2];
            $postComment->content = $line[3];
            $postComment->created_at = now();
            $postComment->updated_at = now();

            $postComment->save();
        }
    }
}
