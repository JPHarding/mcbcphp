<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\CalcService;

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
        $colourDepth = (int) $this->argument("colour_depth");
        $refreshRate = (int) $this->argument("refresh_rate");
        $overhead = (float) $this->argument("overhead");

        // User input rendering.
        $output = <<<HTML
        <div class="mb-1">
            <div>
                <span class="text-gray-400">Resolution: </span>
                <span class="font-bold text-white ml-1">{$width} x {$height}</span>
            </div>
            <div>
                <span class="text-gray-400">Colour Depth: </span>
                <span class="font-bold text-white ml-1">{$colourDepth}</span>
            </div>
            <div>
                <span class="text-gray-400">Refresh Rate: </span>
                <span class="font-bold text-white ml-1">{$refreshRate}</span>
            </div>
            <div>
                 <span class="text-gray-400">Overhead: </span>
                 <span class="font-bold text-white ml-1">{$overhead}</span>
            </div>
        </div>
        HTML;

        // Bandwidth calculation results rendering.
        $math = app(CalcService::class)->calculateBandwidth($width, $height, $colourDepth, $refreshRate, $overhead);

        foreach ($math as $sample => $bandwidth) {
            $bandwidthGB = round($bandwidth / 1000000000, 2);
            $minHdmiVersion = app(CalcService::class)->calculateMinHdmiCableVersion($bandwidthGB);
            $minDisplayPortVersion = app(CalcService::class)->calculateMinDisplayPortCableVersion($bandwidthGB);

            $output .= <<<HTML
            <div class="mb-1">
                <div>
                    <span class="text-gray-400">Chroma subsampling:</span>
                    <span class="font-bold text-white ml-1">{$sample}</span>
                </div>
                <div>
                    <span class="text-gray-400">Calculated bandwidth:</span>
                    <span class="font-bold text-white ml-1">{$bandwidthGB}</span>
                    <span class="text-gray-400 ml-1">GB</span>
                </div>
                <h1>Minimum HDMI & DisplayPort version</h1>
                <div>
                    <span class="text-gray-400">Minimum HDMI Version: <span>
                    <span class="font-bold text-white ml-1">{$minHdmiVersion}<span>
                </div>
                <div>
                    <span class="text-gray-400">Minimum DisplayPort Version: <span>
                    <span class="font-bold text-white ml-1">{$minDisplayPortVersion}</span>
                </div>
            </div>
        HTML;
        }

        render(<<<HTML
        <div class="space-y-1">
            {$output}
        </div>
        HTML);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
