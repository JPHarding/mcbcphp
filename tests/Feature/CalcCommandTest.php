<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalcCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->artisan('calc', [
            'width' => 1920,
            'height' => 1080,
            'colour_depth' => 24,
            'refresh_rate' => 60,
        ])
            ->expectsOutputToContain('Resolution: 1920 x 1080')
            ->expectsOutputToContain('Colour Depth: 24')
            ->expectsOutputToContain('Refresh Rate: 60')
            ->expectsOutputToContain('Overhead: 1.2')

            ->expectsOutputToContain('Chroma subsampling: 4:4:4')
            ->expectsOutputToContain('Calculated bandwidth: 3.58 GB')

            ->expectsOutputToContain('Chroma subsampling: 4:2:2')
            ->expectsOutputToContain('Calculated bandwidth: 2.39 GB')

            ->expectsOutputToContain('Chroma subsampling: 4:2:0 / 4:1:1')
            ->expectsOutputToContain('Calculated bandwidth: 1.79 GB')

            ->expectsOutputToContain('Minimum HDMI Version')
            ->expectsOutputToContain('Minimum DisplayPort Version')
            ->assertExitCode(0);
    }
}
