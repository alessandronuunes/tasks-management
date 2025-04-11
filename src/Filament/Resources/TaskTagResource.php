<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;

use Alessandronuunes\TasksManagement\Models\TaskTag;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskTagResource extends Resource
{
    protected static ?string $model = TaskTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $slug = 'task/tags';

    public static function getNavigationParentItem(): ?string
    {
        return __('tasks-management::task_tags.navigation.parent');
    }
    public static function getLabel(): string
    {
        return __('tasks-management::task_tags.label.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('tasks-management::task_tags.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('tasks-management::task_tags.fields.name'))
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('tasks-management::task_tags.fields.color'))
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth('md'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskTags::route('/'),
        ];
    }
}