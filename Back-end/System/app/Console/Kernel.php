<?php

namespace App\Console;

use App\Models\Job;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sanctum:prune-expired --hours=24')->daily();

        // check the jobs table daily, if the deadline is passed, change the status to 'Ngừng tuyển'
        $schedule->call(function () {
            $jobs = Job::all();
            foreach ($jobs as $job) {
                if ($job->deadline < now()) {
                    $job->status = 'Ngừng tuyển';
                    $job->save();
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
