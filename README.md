# Mcbcphp

Mcbcphp is a PHP CLI utility (built with Laravel Zero) for calculating display-link bandwidth requirements from core video parameters such as resolution, color depth, refresh rate, and protocol overhead.

It computes required bandwidth for multiple chroma subsampling modes and recommends the minimum HDMI and DisplayPort generations that can carry each computed bandwidth.

## Features

- Fast command-line bandwidth calculation for display configurations.
- Supports key video input parameters:
  - Width (pixels)
  - Height (pixels)
  - Color depth (bits per pixel)
  - Refresh rate (Hz)
  - Optional overhead multiplier (default: `1.2`)
- Calculates outputs for multiple chroma subsampling profiles:
  - `4:4:4`
  - `4:2:2`
  - `4:2:0 / 4:1:1`
- Suggests minimum compatible cable/protocol version for:
  - HDMI
  - DisplayPort
- Terminal-friendly formatted output via Termwind.
- Includes Pest-based test scaffolding and a feature test for the `calc` command.
- Supports standalone binary packaging configuration via Box.

## Project Structure

- `app/Commands/CalcCommand.php` — CLI command definition and orchestration.
- `app/Services/CalcService.php` — bandwidth formulas and cable version matching.
- `app/Services/RenderService.php` — formatted output rendering.
- `config/commands.php` — command discovery/default command behavior.
- `mcbcphp` — executable PHP entrypoint.
- `box.json` — standalone build configuration.

## Requirements

- PHP `^8.2`
- Composer `^2`

## Installation

### 1) Clone the repository

```bash
git clone <your-repo-url> mcbcphp
cd mcbcphp
```

### 2) Install dependencies

```bash
composer install
```

### 3) Run the CLI

```bash
php mcbcphp
```

> If dependencies are installed correctly, this will show the Laravel Zero command summary/help output.

## Usage

### Command syntax

```bash
php mcbcphp calc {width} {height} {colour_depth} {refresh_rate} {overhead=1.2}
```

### Parameters

- `width` (int): Display width in pixels.
- `height` (int): Display height in pixels.
- `colour_depth` (int): Bits per pixel (for example `24` or `30`).
- `refresh_rate` (int): Refresh rate in Hz.
- `overhead` (float, optional): Protocol overhead multiplier. Defaults to `1.2`.

### Example

```bash
php mcbcphp calc 1920 1080 24 60
```

```bash
php mcbcphp calc 3840 2160 30 120 1.2
```

### Output includes

- Input summary (resolution, color depth, refresh rate, overhead)
- Computed bandwidth for each chroma subsampling mode
- Minimum HDMI version suggestion
- Minimum DisplayPort version suggestion

## Configuration

### Application config

- `config/app.php`
  - App name (`Mcbcphp`)
  - Version sourced from `app('git.version')`
  - Environment default (`development`)

### Command config

- `config/commands.php`
  - Default command is Laravel Console Summary when no command is provided.
  - Commands are auto-loaded from `app/Commands`.

### Packaging config

- `box.json`
  - Includes `app`, `bootstrap`, `config`, and `vendor`
  - Uses gzip compression and PHP/JSON compactors
  - Useful for building distributable PHAR artifacts

## Dependencies

### Runtime

- `laravel-zero/framework` `^12.0.2`

### Development

- `laravel/pint`
- `mockery/mockery`
- `pestphp/pest`

See `composer.json` and `composer.lock` for full dependency graph and pinned versions.

## Testing

Run tests with:

```bash
./vendor/bin/pest
```

or

```bash
php artisan test
```

> In a fresh environment, you must install dependencies first.

## Build / Distribution

This project includes Box configuration (`box.json`) and platform launchers in `builds/`:

- `builds/mcbcphp` (Unix-like wrapper)
- `builds/mcbcphp.bat` (Windows wrapper)

If you package as PHAR, ensure wrappers and execution permissions are configured appropriately.

## License

This project is licensed under the MIT License.
See [LICENSE](LICENSE).

## Notes

- The project started from a Laravel Zero template and has been customized to implement a display bandwidth calculator command.
- If you extend this tool, consider adding argument validation (for positive numeric ranges) and additional tests for edge cases and higher-end cable standards.
