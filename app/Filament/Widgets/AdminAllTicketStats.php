<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as StatsOverviewBaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminAllTicketStats extends StatsOverviewBaseWidget
{
    protected ?string $heading = 'All Tickets Overall';

    protected int | array | null $columns = 4;

    protected function getStats(): array
    {
        $user = Filament::auth()->user() ?? auth()->user();

        if (! $user) {
            return [
                Stat::make('All Tickets Total', 0)
                    ->description('All tickets in the system')
                    ->color('primary')
                    ->icon('heroicon-o-users'),

                Stat::make('All Tickets Open', 0)
                    ->description('Waiting to be started')
                    ->color('warning')
                    ->icon('heroicon-o-exclamation-circle'),

                Stat::make('All Tickets In Progress', 0)
                    ->description('Currently being worked on')
                    ->color('info')
                    ->icon('heroicon-o-arrow-path'),

                Stat::make('All Tickets Closed', 0)
                    ->description('Completed tickets')
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ];
        }

        $allAdminsInProgress = Ticket::query()
            ->where('status', 'In Progress')
            ->count();

        $allAdminsOpen = Ticket::query()
            ->where('status', 'Open')
            ->count();

        $allAdminsClosed = Ticket::query()
            ->where('status', 'Closed')
            ->count();

        $allAdminsTotal = Ticket::query()->count();

        return [
            Stat::make('All Tickets Total', $allAdminsTotal)
                ->description('All tickets in the system')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('All Tickets Open', $allAdminsOpen)
                ->description('Waiting to be started')
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle'),

            Stat::make('All Tickets In Progress', $allAdminsInProgress)
                ->description('Currently being worked on')
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            Stat::make('All Tickets Closed', $allAdminsClosed)
                ->description('Completed tickets')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
