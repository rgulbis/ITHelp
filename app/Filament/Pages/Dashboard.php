<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminAllTicketStats;
use App\Filament\Widgets\AdminTicketStats;
use App\Filament\Widgets\UserTicketStats;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        $user = Filament::auth()->user() ?? auth()->user();

        if (! $user) {
            return [
                UserTicketStats::class,
            ];
        }

        // Admin sees their assigned tickets
        if (strtolower((string) $user->role) === 'admin') {
            return [
                AdminTicketStats::class,
                AdminAllTicketStats::class,
            ];
        }

        // Regular users see their own tickets
        return [
            UserTicketStats::class,
        ];
    }
}
