<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\render;

class CalcCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'calc
        {width : Screen width in pixels}
        {height : Screen height in pixels}
        {colour_depth : Bits per pixel eg 24 or 30}
        {refresh_rate : Refresh rate in Hz}
        {overhead=1.2 : Bandwidth overhead multiplier default 1.2}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Calculate bandwidth requirements for a display configuration';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $width = (int) $this->argument("width");
        $height = (int) $this->argument("height");
        $colour_depth = (int) $this->argument("colour_depth");
        $refresh_rate = (int) $this->argument("refresh_rate");
        $overhead = (float) $this->argument("overhead");
        $math =  $width * $height * $colour_depth * $refresh_rate * $overhead;
        $math = (string) $math;

        render($math);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
