<?php

namespace App\Filament\User\Resources\TicketResource\Pages;

use App\Filament\User\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = 'Open'; // Default status

        return $data;
    }
}