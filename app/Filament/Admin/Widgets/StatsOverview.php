<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', Siswa::count())
                ->description('Siswa aktif terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Guru', Guru::count())
                ->description('Pengajar aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Total Kelas', Kelas::count())
                ->description('Kelas tersedia')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('primary'),
        ];
    }
}
