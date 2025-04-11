<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;

use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\RelationManagers\CommentsRelationManager;
use Alessandronuunes\TasksManagement\Models\Task;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    public static function getNavigationIcon(): ?string
    {
        return config('tasks-management.navigation.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return config('tasks-management.navigation.sort');
    }

    public static function getLabel(): string
    {
        return __('tasks-management::tasks.label.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('tasks-management::tasks.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->hiddenLabel()
                            ->placeholder(__('tasks-management::tasks.placeholders.name'))
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->hiddenLabel()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('tasks-management::tasks.fields.status'))
                            ->options(TaskStatus::class)
                            ->enum(TaskStatus::class)
                            ->required()
                            ->default(TaskStatus::Pending)
                            ->columnSpan(1),
                        Forms\Components\Select::make('priority')
                            ->label(__('tasks-management::tasks.fields.priority'))
                            ->options(PriorityType::class)
                            ->required()
                            ->default(PriorityType::Low)
                            ->columnSpan(1),
                        Forms\Components\Select::make('users')
                            ->label(__('tasks-management::tasks.fields.users'))
                            ->relationship('users', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpan(1),
                ]),
                Forms\Components\Grid::make()
                    ->columns(12)
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label(__('tasks-management::tasks.fields.starts_at'))
                            ->live()
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->before(fn (Get $get): ?string => $get('ends_at'))
                            ->maxDate(fn (Get $get): string|Carbon => $get('ends_at') ?: now()->addYear())
                            ->columnSpan(6),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label(__('tasks-management::tasks.fields.ends_at'))
                            ->live()
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->after('starts_at')
                            ->minDate(fn (Get $get): ?string => $get('starts_at'))
                            ->columnSpan(6),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tasks-management::tasks.fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('tasks-management::tasks.fields.status'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('tasks-management::tasks.fields.priority'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('tasks-management::tasks.fields.type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tasks-management::tasks.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
