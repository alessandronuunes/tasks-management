<?php

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\RelationManagers;

use Filament\Tables;
use Filament\Infolists;
use Filament\Tables\Table;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Alessandronuunes\TasksManagement\Enums\AuditEventType;
use Filament\Infolists\Components\Tabs;

class AuditsRelationManager extends RelationManager
{
    protected static string $relationship = 'audits';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('tasks-management::labels.audits.plural');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->label(__('tasks-management::fields.audits.event'))
                    ->formatStateUsing(fn (string $state): string => AuditEventType::from($state)->getLabel())
                    ->badge()
                    ->color(fn (string $state): string => AuditEventType::from($state)->getColor()),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('tasks-management::fields.audits.user'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tasks-management::fields.audits.created_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('tasks-management::fields.audits.ip_address'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->infolist([
                        Infolists\Components\Section::make(__('tasks-management::fields.audits.sections.details'))
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('event')
                                            ->label(__('tasks-management::fields.audits.event'))
                                            ->badge()
                                            ->color(fn (string $state): string => AuditEventType::from($state)->getColor()),
                                        TextEntry::make('created_at')
                                            ->label(__('tasks-management::fields.audits.created_at'))
                                            ->dateTime('d/m/Y H:i:s'),
                                        TextEntry::make('user.name')
                                            ->label(__('tasks-management::fields.audits.user'))
                                            ->default(__('tasks-management::fields.audits.messages.system')),
                                        TextEntry::make('ip_address')
                                            ->label(__('tasks-management::fields.audits.ip_address')),
                                        TextEntry::make('user_agent')
                                            ->columnSpanFull()
                                            ->label(__('tasks-management::fields.audits.user_agent')),
                                    ]),
                            ]),
                        Tabs::make('Values')
                            ->columnSpanFull()
                            ->tabs([
                                Tabs\Tab::make(__('tasks-management::fields.audits.old_value'))
                                    ->schema([
                                        TextEntry::make('old_values')
                                            ->hiddenLabel()
                                            ->extraAttributes(['class' => 'prose max-w-none'])
                                            ->html(),
                                    ]),
                                Tabs\Tab::make(__('tasks-management::fields.audits.new_value'))
                                    ->schema([
                                        TextEntry::make('new_values')
                                            ->hiddenLabel()
                                            ->extraAttributes(['class' => 'prose max-w-none'])
                                            ->html(),
                                    ]),
                            ]),
                    ]),
            ])
            ->bulkActions([
                //
            ]);
    }


}