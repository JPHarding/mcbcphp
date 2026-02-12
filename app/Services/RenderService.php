<?php

namespace App\Services;

class RenderService
{
    public function outputHtmlUserInput(int $width, int $height, int $colourDepth, int $refreshRate, float $overhead): string
    {
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

        return $output;
    }


    public function outputHtmlCalcResult(string $sample, float $bandwidth, string $minHdmiVersion, string $minDisplayPortVersion): string
    {
        $output = <<<HTML
            <div class="mb-1">
                <div>
                    <span class="text-gray-400">Chroma subsampling:</span>
                    <span class="font-bold text-white ml-1">{$sample}</span>
                </div>
                <div>
                    <span class="text-gray-400">Calculated bandwidth:</span>
                    <span class="font-bold text-white ml-1">{$bandwidth}</span>
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

        return $output;
    }

    public function renderResult(string $userInputHtml, string $calcHtml)
    {
        $innerHtml = $userInputHtml . $calcHtml;
        $output = <<<HTML
        <div class="space-y-1">
            {$innerHtml}
        </div>
        HTML;

        return $output;
    }
}
