<?php

namespace App\Filament\Guru\Widgets;

use App\Models\HasilUjian;
use App\Models\Ujian;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class GuruAktivitasSiswaChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Siswa Mengerjakan Ujian (30 Hari Terakhir)';

    protected static string $color = 'info';

    protected int|string|array $columnSpan = 'half';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $guru = auth()->user()->guru;
        if (! $guru) {
            return ['datasets' => [], 'labels' => []];
        }

        $ujianIds = Ujian::where('id_guru', $guru->id_guru)->pluck('id_ujian');

        $data = Trend::query(HasilUjian::whereIn('id_ujian', $ujianIds))
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Siswa Selesai',
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
