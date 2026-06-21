<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class MetricsController extends Controller
{
    public function __invoke(): Response
    {
        $startedAt = hrtime(true);
        $databaseUp = 1;

        try {
            DB::select('SELECT 1');
        } catch (Throwable) {
            $databaseUp = 0;
        }

        $databaseDuration = (hrtime(true) - $startedAt) / 1_000_000_000;
        $environment = $this->escapeLabel((string) app()->environment());
        $version = $this->escapeLabel((string) config('app.version', 'unknown'));

        $metrics = [
            '# HELP laravel_app_info Informasi aplikasi Laravel.',
            '# TYPE laravel_app_info gauge',
            sprintf('laravel_app_info{environment="%s",version="%s"} 1', $environment, $version),
            '# HELP laravel_app_up Menunjukkan backend Laravel dapat melayani scrape.',
            '# TYPE laravel_app_up gauge',
            'laravel_app_up 1',
            '# HELP laravel_database_up Menunjukkan koneksi database tersedia.',
            '# TYPE laravel_database_up gauge',
            "laravel_database_up {$databaseUp}",
            '# HELP laravel_database_probe_duration_seconds Durasi pemeriksaan koneksi database.',
            '# TYPE laravel_database_probe_duration_seconds gauge',
            sprintf('laravel_database_probe_duration_seconds %.6f', $databaseDuration),
            '# HELP php_process_memory_bytes Memori yang sedang digunakan proses PHP.',
            '# TYPE php_process_memory_bytes gauge',
            'php_process_memory_bytes '.memory_get_usage(true),
        ];

        return response(implode("\n", $metrics)."\n", 200, [
            'Content-Type' => 'text/plain; version=0.0.4; charset=utf-8',
            'Cache-Control' => 'no-store',
        ]);
    }

    private function escapeLabel(string $value): string
    {
        return str_replace(["\\", "\n", '"'], ["\\\\", '\\n', '\\"'], $value);
    }
}
