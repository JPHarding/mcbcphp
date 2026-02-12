<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\CalcService;
use App\Services\RenderService;

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


        // Bandwidth calculation results rendering.
        $math = app(CalcService::class)->calculateBandwidth($width, $height, $colourDepth, $refreshRate, $overhead);

        $outputCalcHtml = "";

        foreach ($math as $sample => $bandwidth) {
            $minHdmiVersion = app(CalcService::class)->calculateMinHdmiCableVersion($bandwidth);
            $minDisplayPortVersion = app(CalcService::class)->calculateMinDisplayPortCableVersion($bandwidth);
            $outputCalcHtml .= app(RenderService::class)->outputHtmlCalcResult($sample, $bandwidth, $minHdmiVersion, $minDisplayPortVersion);
        }

        $userInputHtml = app(RenderService::class)->outputHtmlUserInput($width, $height, $colourDepth, $refreshRate, $overhead);
        render(app(RenderService::class)->renderResult($userInputHtml, $outputCalcHtml));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
