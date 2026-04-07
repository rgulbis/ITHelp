<?php

namespace App\Filament\User\Resources\TicketResource\Pages;

use App\Filament\User\Resources\TicketResource;
use App\Models\Comment;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    // public function infolist(Schema $schema): Schema
    // {
    //     return $schema
    //         ->schema([
    //             Infolists\Components\Section::make('Ticket Details')
    //                 ->schema([
    //                     Infolists\Components\TextEntry::make('first_name'),
    //                     Infolists\Components\TextEntry::make('last_name'),
    //                     Infolists\Components\TextEntry::make('class_department')->label('Class/Department'),
    //                     Infolists\Components\TextEntry::make('category'),
    //                     Infolists\Components\TextEntry::make('priority'),
    //                     Infolists\Components\TextEntry::make('title'),
    //                     Infolists\Components\TextEntry::make('description'),
    //                     Infolists\Components\TextEntry::make('status'),
    //                     Infolists\Components\TextEntry::make('assignedUser.name')->label('Assigned To'),
    //                     Infolists\Components\TextEntry::make('created_at')->dateTime(),
    //                     Infolists\Components\TextEntry::make('updated_at')->dateTime(),
    //                 ])->columns(2),
    //             Infolists\Components\Section::make('Images')
    //                 ->schema([
    //                     Infolists\Components\ImageEntry::make('images')
    //                         ->disk('public')
    //                         ->height(200),
    //                 ])
    //                 ->visible(fn ($record) => !empty($record->images)),
    //             Infolists\Components\Section::make('Comments')
    //                 ->schema([
    //                     Infolists\Components\RepeatableEntry::make('comments')
    //                         ->schema([
    //                             Infolists\Components\TextEntry::make('user.name')->label('User'),
    //                             Infolists\Components\TextEntry::make('message'),
    //                             Infolists\Components\TextEntry::make('created_at')->dateTime(),
    //                         ])
    //                         ->columns(1),
    //                 ]),
    //         ]);
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add_comment')
                ->label('Add Comment')
                ->icon('heroicon-o-chat-bubble-left')
                ->form([
                    Forms\Components\Textarea::make('message')
                        ->required(),
                ])
                ->action(function (array $data) {
                    Comment::create([
                        'ticket_id' => $this->record->id,
                        'user_id' => auth()->id(),
                        'message' => $data['message'],
                    ]);
                }),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}