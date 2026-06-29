<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use App\Models\Ujian;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsGuruWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $guru = auth()->user()->guru;

        if (! $guru) {
            return [];
        }

        // Get IDs of exams created by this Guru
        $ujianIds = Ujian::where('id_guru', $guru->id_guru)->pluck('id_ujian');

        $siswaSelesaiCount = \App\Models\HasilUjian::whereIn('id_ujian', $ujianIds)
            ->whereMonth('created_at', now()->month)
            ->count();

        $rataRataNilai = \App\Models\HasilUjian::whereIn('id_ujian', $ujianIds)
            ->whereMonth('created_at', now()->month)
            ->avg('nilai');

        return [
            Stat::make('Siswa Selesai Ujian (Bulan Ini)', $siswaSelesaiCount)
                ->description('Total siswa yang sudah mengerjakan')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Rata-Rata Nilai (Bulan Ini)', number_format($rataRataNilai ?? 0, 1))
                ->description('Performansi rata-rata siswa')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Total Ujian Anda', $ujianIds->count())
                ->description('Ujian yang Anda buat')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),
        ];
    }
}
