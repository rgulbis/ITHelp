<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as StatsOverviewBaseWidget;
use Illuminate\Support\Facades\Auth;

class AdminTicketStats extends StatsOverviewBaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        $inProgress = Ticket::where('assigned_to', $user->id)
            ->where('status', 'In Progress')
            ->count();

        $open = Ticket::where('assigned_to', $user->id)
            ->where('status', 'Open')
            ->count();

        $closed = Ticket::where('assigned_to', $user->id)
            ->where('status', 'Closed')
            ->count();

        $total = $inProgress + $open + $closed;

        return [
            StatsOverviewBaseWidget\Stat::make('Total Assigned', $total)
                ->description('All tickets assigned to you')
                ->color('primary')
                ->icon('heroicon-o-clipboard-document'),

            StatsOverviewBaseWidget\Stat::make('Open', $open)
                ->description('Waiting to be started')
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle'),

            StatsOverviewBaseWidget\Stat::make('In Progress', $inProgress)
                ->description('Currently working on')
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            StatsOverviewBaseWidget\Stat::make('Closed', $closed)
                ->description('Completed tickets')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
