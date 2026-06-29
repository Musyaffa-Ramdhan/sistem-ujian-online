<?php

namespace App\Filament\Admin\Widgets;

use App\Models\LoginActivity;
use App\Models\Role;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class GuruLoginChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Login Guru (7 Hari Terakhir)';

    protected static string $color = 'warning';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2; // Below Stats

    protected function getData(): array
    {
        // Get Guru Role ID
        $guruRole = Role::where('nama_role', 'Guru')->first();
        if (! $guruRole) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $data = Trend::query(LoginActivity::where('id_role', $guruRole->id_role))
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
