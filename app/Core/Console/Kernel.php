<?php

namespace LocalheroPortal\Core\Console;

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
        'LocalheroPortal\LLI\Console\Commands\GetInsights',
        'LocalheroPortal\LLI\Console\Commands\GetLocalRankings',
    ];

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $root = dirname(dirname(__DIR__));
        $this->load(__DIR__ . '/Commands');
        $this->load($root . '/LLI/Console/Commands');
        $this->load($root . '/Callcenter/Console/Commands');
        $this->load($root . '/Core/Console/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('insights:get')->daily();
        $schedule->command('localrankings:get')->daily();
        /*$schedule->command('horizon:snapshot')->everyFiveMinutes();*/
        $schedule->command('telescope:prune --hours=72')->daily();
        $schedule->command('callcenter:send-recall-notifications')->everyMinute();
        $schedule->command('callcenter:reset-pipeline')->dailyAt('0:0');
        $schedule->command('import:googleCategories')->weekly();
    }
}
