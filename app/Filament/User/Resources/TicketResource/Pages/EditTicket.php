<?php

namespace App\Filament\User\Resources\TicketResource\Pages;

use App\Filament\User\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['full_name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $fullName = trim((string) ($data['full_name'] ?? ''));
        [$firstName, $lastName] = array_pad(preg_split('/\s+/', $fullName, 2) ?: [], 2, '');

        $data['first_name'] = $firstName;
        $data['last_name'] = $lastName;
        unset($data['full_name']);

        return $data;
    }

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