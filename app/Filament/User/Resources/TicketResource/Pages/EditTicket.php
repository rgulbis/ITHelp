<?php

namespace App\Filament\User\Resources\TicketResource\Pages;

use App\Filament\User\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back')
                ->icon('heroicon-o-arrow-left')
                ->url(fn (): string => url()->previous() !== request()->fullUrl()
                    ? url()->previous()
                    : static::getResource()::getUrl('index')),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}