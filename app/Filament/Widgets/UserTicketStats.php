<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as StatsOverviewBaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserTicketStats extends StatsOverviewBaseWidget
{
    protected function getStats(): array
    {
        $user = Filament::auth()->user();

        if (! $user) {
            return [
                Stat::make('Total Tickets', 0)
                    ->description('All your submitted tickets')
                    ->color('primary')
                    ->icon('heroicon-o-clipboard-document'),

                Stat::make('Pending', 0)
                    ->description('Awaiting admin review')
                    ->color('warning')
                    ->icon('heroicon-o-clock'),

                Stat::make('In Progress', 0)
                    ->description('Being worked on')
                    ->color('info')
                    ->icon('heroicon-o-arrow-path'),

                Stat::make('Closed', 0)
                    ->description('Resolved tickets')
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ];
        }

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
            Stat::make('Total Tickets', $total)
                ->description('All your submitted tickets')
                ->color('primary')
                ->icon('heroicon-o-clipboard-document'),

            Stat::make('Pending', $open)
                ->description('Awaiting admin review')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('In Progress', $inProgress)
                ->description('Being worked on')
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            Stat::make('Closed', $closed)
                ->description('Resolved tickets')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
