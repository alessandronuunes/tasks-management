<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;

use Alessandronuunes\TasksManagement\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?int $navigationSort = 30;

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
                Forms\Components\Tabs::make()
                    ->columnSpanFull()
                    ->extraAttributes(['class' => '!ring-0 !shadow-none'])
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('tasks-management::tasks.tabs.basic'))
                            ->columns(12)
                            ->schema(static::getFieldsTasksBasic()),
                        Forms\Components\Tabs\Tab::make(__('tasks-management::tasks.tabs.advanced'))
                            ->schema(static::getFieldsTasksAdvanced()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\Layout\Stack::make([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tasks-management::tasks.columns.title'))
                    ->icon(fn (Task $record) => ! static::verifyReadAt($record) ? 'heroicon-o-finger-print' : null)
                    ->iconColor('indigo')
                    ->sortable()
                    ->searchable()
                    ->limit(),
                // ... rest of the columns configuration
            ])->space(3),
        ];

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns($columns)
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->extraModalWindowAttributes(['class' => '[&_.fi-modal-content]:!p-0'])
                    ->modalWidth(MaxWidth::TwoExtraLarge),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ]);
    }

    // ... rest of the methods remain similar but with updated namespaces and translations
}
