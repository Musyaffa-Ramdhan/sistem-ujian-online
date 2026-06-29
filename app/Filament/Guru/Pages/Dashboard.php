<?php

namespace App\Filament\Guru\Pages;

use App\Filament\Guru\Widgets\StatsGuruWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            StatsGuruWidget::class,
        ];
    }
}
