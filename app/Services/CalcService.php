<?php

namespace App\Services;

final class CalcService
{
    /**
     * Chroma subsampling ratios relative to full 4:4:4 sampling.
     *
     * @var array<string, float>
     */
    private static array $chromaSampling = [
        "4:4:4" => 1.0,
        "4:2:2" => 2 / 3,
        "4:2:0 / 4:1:1" => 0.5
    ];

    /**
     * @var array<string,float>
     */
    private static array $maxHdmiBandwidthGbps = [
        'HDMI 1.0 to 1.2a' => 4.95,
        'HDMI 1.3 to 1.4b' => 10.2,
        'HDMI 2.0 to 2.0b' => 18.0,
        'HDMI 2.1 to 2.1b' => 48.0,
        'HDMI 2.2' => 96.0
    ];

    /**
     * @var array<string, float>
     * */
    private static array $maxDisplayPortBandwidthGbps = [
        'DisplayPort 1.0 to 1.1a' => 10.80,
        'DisplayPort 1.2 to 1.2a' => 21.60,
        'DisplayPort 1.3' => 32.40,
        'DisplayPort 1.4 to 1.4a' => 32.40,
        'DisplayPort 2.0 to 2.1a' => 80.00,
    ];

    /**
     * Calculates bandwidths for each chroma sampling rate.
     *
     * @return array<string, float>
     */
    public function calculateBandwidth(int $width, int $height, int $colourDepth, int $refreshRate, float $overhead): array
    {
        /** @var array<string, float> $bandwidths */
        $bandwidths = [];

        foreach (self::$chromaSampling as $sample => $rate) {
            $effectiveColourDepth = $colourDepth * $rate;

            $bandwidth = $width * $height * $effectiveColourDepth * $refreshRate * $overhead;
            $bandwidths[$sample] = (float) $bandwidth;
        }
        return $bandwidths;
    }

    public function calculateMinHdmiCableVersion(float $calculatedBandwidth): string
    {
        foreach (self::$maxHdmiBandwidthGbps as $hdmiVersion => $supportedBandwidth) {
            if ($supportedBandwidth >= $calculatedBandwidth) {
                return $hdmiVersion;
            }
        }
        return "Error with HDMI Bandwidth Calculation";
    }

    public function calculateMinDisplayPortCableVersion(float $calculatedBandwidth): string
    {
        foreach (self::$maxDisplayPortBandwidthGbps as $displayPortVersion => $supportedBandwidth) {
            if ($supportedBandwidth >= $calculatedBandwidth) {
                return $displayPortVersion;
            }
        }
        return "Error with DisplayPort Bandwidth Calculation";
    }
}
