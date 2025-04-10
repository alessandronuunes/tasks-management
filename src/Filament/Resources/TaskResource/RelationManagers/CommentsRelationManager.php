<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('tasks-management::tasks.comments');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('content')
                    ->hiddenLabel()
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(1000),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Split::make([
                    Tables\Columns\ImageColumn::make('user.profile_photo_url')
                        ->circular()
                        ->grow(false),
                    Tables\Columns\TextColumn::make('user.name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('content')
                        ->html()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->since()
                        ->dateTimeTooltip(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading(__('tasks-management::tasks.new_comment'))
                    ->modalIcon('heroicon-o-chat-bubble-left')
                    ->modalWidth('2xl')
                    ->label(__('tasks-management::tasks.comment')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
