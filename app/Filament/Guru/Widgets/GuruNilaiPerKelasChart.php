<?php

namespace App\Filament\Guru\Widgets;

use App\Models\HasilUjian;
use App\Models\Ujian;
use Filament\Widgets\ChartWidget;

class GuruNilaiPerKelasChart extends ChartWidget
{
    protected static ?string $heading = 'Rata-Rata Nilai Per Kelas (Bulan Ini)';

    protected static string $color = 'success';

    protected int|string|array $columnSpan = 'half';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $guru = auth()->user()->guru;
        if (! $guru) {
            return ['datasets' => [], 'labels' => []];
        }

        // Query: Join HasilUjian -> Ujian -> Kelas
        // To detect AVG Nilai per Kelas for this Guru
        $data = HasilUjian::query()
            ->join('ujians', 'hasil_ujians.id_ujian', '=', 'ujians.id_ujian')
            ->join('kelas', 'ujians.id_kelas', '=', 'kelas.id_kelas')
            ->where('ujians.id_guru', $guru->id_guru)
            ->whereMonth('hasil_ujians.created_at', now()->month)
            ->selectRaw('kelas.nama_kelas as nama_kelas, AVG(hasil_ujians.nilai) as rata_rata')
            ->groupBy('kelas.nama_kelas')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Rata-Rata Nilai',
                    'data' => $data->pluck('rata_rata'),
                    'backgroundColor' => '#22c55e', // Success Green
                ],
            ],
            'labels' => $data->pluck('nama_kelas'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
