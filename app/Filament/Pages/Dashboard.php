<?php

namespace App\Filament\Pages;

use App\Models\Ticket;
use App\Filament\Widgets\AdminTicketStats;
use App\Filament\Widgets\UserTicketStats;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        $user = Auth::user();

        // Admin sees their assigned tickets
        if ($user->role === 'admin') {
            return [
                AdminTicketStats::class,
            ];
        }

        // Regular users see their own tickets
        return [
            UserTicketStats::class,
        ];
    }
}
