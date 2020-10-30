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
        Commands\UpdateCompetitionList::class,
        Commands\UpdateRaceList::class,
        Commands\UpdateEventList::class,
        Commands\UpdateResultList::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $dateLastMonth = new \DateTime('NOW');
        $dateLastMonth->modify('-1 month');

//         $schedule->command('inspire')->hourly();

        // Gestion de la récupération des Compétitions
        $schedule->command('command:update-competition-list', [date('Y')])->twiceDaily(15,16,17,18);;
//        $schedule->command('command:update-competition-list')->everyMinute();

        // Gestion de la récupération des Courses
        $schedule->command('command:update-race-list', ['current'])->cron('* 16-18 * * *'); // Toutes les minutes entre 16h et 18h
        $schedule->command('command:update-race-list', ['current'])->hourlyAt(21); // Toutes les heures à 21 minutes
        $schedule->command('command:update-race-list', ['--dateStartAfter=' . $dateLastMonth->format('Y-m-d')])->dailyAt('19:03'); // Tous les jours à 19h03

        // Gestion de la récupération des Classements étapes
        $schedule->command('command:update-event-list', ['current'])->cron('* 16-18 * * *'); // Toutes les minutes entre 16h et 18h
        $schedule->command('command:update-event-list', ['current'])->hourlyAt(31); // Toutes les heures à 21 minutes
        $schedule->command('command:update-event-list', ['--dateStartAfter=' . $dateLastMonth->format('Y-m-d')])->dailyAt('19:03'); // Tous les jours à 19h03

        // Gestion de la récupération des Résultats
        $schedule->command('command:update-result-list', ['current'])->cron('* 16-18 * * *'); // Toutes les minutes entre 16h et 18h
        $schedule->command('command:update-result-list', ['current'])->hourlyAt(41); // Toutes les heures à 21 minutes

        // Test
//        $schedule->command('command:update-competition-list')->hourlyAt(48);
//        $schedule->command('command:update-race-list', ['current'])->hourlyAt(48);
//        $schedule->command('command:update-event-list', ['current'])->hourlyAt(48);
//        $schedule->command('command:update-result-list', ['current'])->hourlyAt(48);

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
