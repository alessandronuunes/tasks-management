<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Alessandronuunes\TasksManagement\Models\TaskTag;
use Alessandronuunes\TasksManagement\Traits\HasResourceConfig;
use Alessandronuunes\TasksManagement\Traits\AuthorizedUsersTrait;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource\Pages;

class TaskTagResource extends Resource
{
    use HasResourceConfig;
    use AuthorizedUsersTrait;
    protected static ?string $model = TaskTag::class;
    public static function getLabel(): string
    {
        return __('tasks-management::labels.task_tags.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('tasks-management::labels.task_tags.plural');
    }
    public static function getNavigationParentItem(): ?string
    {
        return __('tasks-management::navigation.parent.task_tags');
    }

    public static function canViewAny(): bool
    {
        return self::isAuthorized();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('tasks-management::fields.common.name'))
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('tasks-management::fields.task_tags.color'))
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tasks-management::fields.common.name')),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('tasks-management::fields.task_tags.color')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tasks-management::fields.common.created_at'))
                    ->dateTime(),
            ])
            ->filters([])
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