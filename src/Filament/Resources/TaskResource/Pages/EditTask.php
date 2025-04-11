<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;

use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Enums\TaskType;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Alessandronuunes\TasksManagement\Models\Task;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('tasks-management::tasks.sections.task_data'))
                ->description()
                    ->columns(2)
                    ->columnSpan(['lg' => fn (?Task $record) => $record === null ? 3 : 2])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->hiddenLabel()
                            ->placeholder(fn (Get $get) => TaskType::tryFrom($get('type') ?? '')?->getTitle())
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->hiddenLabel()
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label(__('tasks-management::tasks.fields.starts_at'))
                            ->live()
                            ->native(false)
                            ->columnSpan(1)
                            ->displayFormat('d M Y H:i')
                            ->before(fn (Get $get): ?string => $get('ends_at'))
                            ->maxDate(fn (Get $get): string|Carbon => $get('ends_at') ?: now()->addYear()),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label(__('tasks-management::tasks.fields.ends_at'))
                            ->live()
                            ->columnSpan(1)
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->after('starts_at')
                            ->minDate(fn (Get $get): ?string => $get('starts_at')),
                    ]),
                Forms\Components\Section::make(__('tasks-management::tasks.sections.properties'))
                    ->description('')
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Forms\Components\Select::make('users')
                            ->relationship('users', 'name')
                            ->label(__('tasks-management::tasks.fields.assigned_users'))
                            ->multiple()
                            ->columnSpanFull(),
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
                            ->default(PriorityType::Low),
                        Forms\Components\Select::make('tags')
                            ->multiple()
                            ->placeholder(__('tasks-management::tasks.placeholders.tags'))
                            ->relationship('tags', 'name')
                            ->createOptionAction(fn ( $action) => $action->modalWidth('md'))
                            
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\ColorPicker::make('color')
                                    ->required(),
                            ])
                            ->preload(),
                    ]),
            ])->columns(3);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        if (! $record->users()->where('users.id', auth()->id())->exists()) {
            $record->users()->attach(auth()->id(), ['read_at' => now()]);
        }
    }
}
