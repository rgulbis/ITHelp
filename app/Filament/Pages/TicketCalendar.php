<?php

namespace App\Filament\Pages;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TicketCalendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Ticket Calendar';
    protected static ?string $slug = 'ticket-calendar';
    protected string $view = 'filament.pages.ticket-calendar';

    public Collection $ticketCounts;
    public Collection $tickets;

    public function mount(): void
    {
        $this->tickets = Ticket::with(['user', 'assignedUser'])->orderByDesc('created_at')->get();
        $this->ticketCounts = Ticket::all()
            ->groupBy(fn (Ticket $ticket) => $ticket->created_at->format('Y-m'))
            ->map(fn (Collection $tickets) => $tickets->count())
            ->sortKeysDesc();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->icon('heroicon-o-arrow-left')
                ->url(url()->previous()),
        ];
    }

    public function getEvents(): array
    {
        return $this->tickets->map(fn($ticket) => [
            'id' => $ticket->id,
            'title' => $ticket->title . ' (' . $ticket->status . ')',
            'start' => $ticket->created_at->timezone('Europe/Riga')->format('Y-m-d\TH:i:s'), // Local time
            'allDay' => false,
            'priority' => $ticket->priority,
            'category' => $ticket->category,
            'url' => route('filament.admin.resources.tickets.view', ['record' => $ticket]),
            'description' => Str::limit($ticket->description ?? '', 100),
        ])->toArray();
    }
}
