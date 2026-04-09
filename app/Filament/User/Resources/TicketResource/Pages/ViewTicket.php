<?php

namespace App\Filament\User\Resources\TicketResource\Pages;

use App\Filament\User\Resources\TicketResource;
use App\Models\Comment;
use Filament\Infolists\Components as InfolistComponents;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\Section::make('Ticket Details')
                    ->schema([
                        InfolistComponents\TextEntry::make('first_name'),
                        InfolistComponents\TextEntry::make('last_name'),
                        InfolistComponents\TextEntry::make('class_department')->label('Class/Department'),
                        InfolistComponents\TextEntry::make('category'),
                        InfolistComponents\TextEntry::make('priority'),
                        InfolistComponents\TextEntry::make('title'),
                        InfolistComponents\TextEntry::make('description'),
                        InfolistComponents\TextEntry::make('status'),
                        InfolistComponents\TextEntry::make('assignedUser.name')->label('Assigned To'),
                        Components\Text::make(function (): HtmlString {
                            $images = $this->record->images;

                            if (is_string($images)) {
                                $decoded = json_decode($images, true);
                                $images = is_array($decoded) ? $decoded : [];
                            }

                            if (empty($images) || ! is_array($images)) {
                                return new HtmlString('<strong>Image Downloads:</strong> No images uploaded');
                            }

                            $links = collect($images)
                                ->filter()
                                ->values()
                                ->map(function (string $path, int $index): string {
                                    $url = e('/storage/' . ltrim($path, '/'));

                                    return '<a href="'.$url.'" download target="_blank" rel="noopener">Download image '.($index + 1).'</a>';
                                })
                                ->implode('<br>');

                            return new HtmlString('<strong>Image Downloads:</strong><br>'.$links);
                        })->columnSpanFull(),
                        InfolistComponents\TextEntry::make('created_at')->dateTime(),
                        InfolistComponents\TextEntry::make('updated_at')->dateTime(),
                    ])->columns(2),
                Components\Section::make('Comments')
                    ->schema([
                        InfolistComponents\RepeatableEntry::make('comments')
                            ->schema([
                                InfolistComponents\TextEntry::make('user.name')->label('User'),
                                InfolistComponents\TextEntry::make('message'),
                                InfolistComponents\TextEntry::make('created_at')->dateTime(),
                            ])
                            ->columns(1),
                    ]),
            ]);
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