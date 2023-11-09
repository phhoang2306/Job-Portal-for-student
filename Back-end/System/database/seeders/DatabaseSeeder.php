<?php

namespace Database\Seeders;

 use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
 use Illuminate\Support\Facades\DB;

 class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        $this->call(
            [
                AdminSeeder::class,
                UserAccountSeeder::class,
                UserProfileSeeder::class,
                UserAchievementSeeder::class,
                UserEducationSeeder::class,
                UserExperienceSeeder::class,
                UserSkillSeeder::class,
                CompanyAccountSeeder::class,
                CompanyProfileSeeder::class,
                EmployerAccountSeeder::class,
                EmployerProfileSeeder::class,
                CVSeeder::class,
                JobSeeder::class,
                JobSkillSeeder::class,
                ApplicationSeeder::class,
                PostSeeder::class,
                PostReportSeeder::class,
                PostCommentSeeder::class,
                JobReportSeeder::class,
                CompanyReportSeeder::class,
                UserHistorySeeder::class,
                TimeTableSeeder::class,
                SavedJobSeeder::class,
                CategorySeeder::class,
                JobCategorySeeder::class,
            ]
        );
    }
}
