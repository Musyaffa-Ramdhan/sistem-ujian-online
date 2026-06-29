<?php

namespace App\Filament\Admin\Widgets;

use App\Models\LoginActivity;
use App\Models\Role;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SiswaLoginChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Login Siswa (7 Hari Terakhir)';

    protected static string $color = 'success';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Get Siswa Role ID
        $siswaRole = Role::where('nama_role', 'Siswa')->first();
        if (! $siswaRole) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $data = Trend::query(LoginActivity::where('id_role', $siswaRole->id_role))
            ->between(
                start: now()->subDays(7),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Login',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
