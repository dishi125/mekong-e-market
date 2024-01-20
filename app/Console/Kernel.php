<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\PlanExpire',
        'App\Console\Commands\CheckPayment',
        'App\Console\Commands\SendNotification',
         'App\Console\Commands\LivetradeNotification',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('plan_expire:users')
                    ->everyMinute();
//                  ->dailyAt("00:00"); /*dishita*/
//                    ->daily();
         $schedule->command('check_payment:users')
                  ->hourly();
         $schedule->command('send_notification:user')
                  ->hourly();
         $schedule->command('livetrade:notification')->everyMinute();

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
