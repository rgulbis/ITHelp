<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as StatsOverviewBaseWidget;
use Illuminate\Support\Facades\Auth;

class UserTicketStats extends StatsOverviewBaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        $open = Ticket::where('user_id', $user->id)
            ->where('status', 'Open')
            ->count();

        $inProgress = Ticket::where('user_id', $user->id)
            ->where('status', 'In Progress')
            ->count();

        $closed = Ticket::where('user_id', $user->id)
            ->where('status', 'Closed')
            ->count();

        $total = $open + $inProgress + $closed;

        return [
            StatsOverviewBaseWidget\Stat::make('Total Tickets', $total)
                ->description('All your submitted tickets')
                ->color('primary')
                ->icon('heroicon-o-clipboard-document'),

            StatsOverviewBaseWidget\Stat::make('Pending', $open)
                ->description('Awaiting admin review')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            StatsOverviewBaseWidget\Stat::make('In Progress', $inProgress)
                ->description('Being worked on')
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            StatsOverviewBaseWidget\Stat::make('Closed', $closed)
                ->description('Resolved tickets')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
