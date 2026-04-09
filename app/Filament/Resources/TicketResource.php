<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\CommentsRelationManager;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas as Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $model = Ticket::class;

    // protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Ticket Details')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('last_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('class_department')
                            ->label('Class/Department')
                            ->disabled(),
                        Forms\Components\Select::make('category')
                            ->options([
                                'Hardware' => 'Hardware',
                                'Software' => 'Software',
                                'Network' => 'Network',
                                'Other' => 'Other',
                            ])
                            ->disabled(),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'Low' => 'Low',
                                'Medium' => 'Medium',
                                'Urgent' => 'Urgent',
                            ])
                            ->disabled(),
                        Forms\Components\TextInput::make('title')
                            ->disabled(),
                        Forms\Components\Textarea::make('description')
                            ->disabled(),
                        Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                                'image/heic',
                                'image/heif',
                            ])
                            ->maxSize(20480)
                            ->directory('tickets')
                            ->disk('public')
                            ->visibility('public')
                            ->openable()
                            ->downloadable(),
                    ])->columns(2),
                Schemas\Components\Section::make('Assignment')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Open' => 'Open',
                                'In Progress' => 'In Progress',
                                'Closed' => 'Closed',
                            ])
                            ->required(),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned To')
                            ->options(\App\Models\User::where('role', 'admin')->pluck('name', 'id'))
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Low' => 'gray',
                        'Medium' => 'warning',
                        'Urgent' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'success',
                        'In Progress' => 'warning',
                        'Closed' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By'),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Open' => 'Open',
                        'In Progress' => 'In Progress',
                        'Closed' => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Hardware' => 'Hardware',
                        'Software' => 'Software',
                        'Network' => 'Network',
                        'Other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'Urgent' => 'Urgent',
                    ]),
            ])
            ->headerActions([])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}