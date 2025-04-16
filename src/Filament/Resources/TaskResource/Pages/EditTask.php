<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;

use Forms\Form;
use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;
use Filament\Resources\Pages\EditRecord;
use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Enums\TaskType;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Traits\UserQueryModifierTrait;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Alessandronuunes\TasksManagement\Filament\Forms\Components\CustomFields;

class EditTask extends EditRecord
{

    use UserQueryModifierTrait;
    protected static string $resource = TaskResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('tasks-management::fields.tasks.sections.task_data'))
                ->description()
                    ->columns(4)
                    ->columnSpan(['lg' => fn (?Task $record) => $record === null ? 3 : 2])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('tasks-management::fields.common.name'))
                            ->placeholder(__('tasks-management::fields.tasks.placeholders.name'))
                            ->columnSpan(3)
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label(__('tasks-management::fields.tasks.status'))
                            ->options(TaskStatus::class)
                            ->enum(TaskStatus::class)
                            ->required()
                            ->default(TaskStatus::Pending)
                            ->columnSpan(1),
                        Forms\Components\RichEditor::make('description')
                            ->label(__('tasks-management::fields.common.description'))
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label(__('tasks-management::fields.tasks.starts_at'))
                            ->live()
                            ->native(false)
                            ->columnSpan(2)
                            ->displayFormat('d M Y H:i')
                            ->before(fn (Get $get): ?string => $get('ends_at'))
                            ->maxDate(fn (Get $get): string|Carbon => $get('ends_at') ?: now()->addYear()),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label(__('tasks-management::fields.tasks.ends_at'))
                            ->live()
                            ->columnSpan(2)
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->after('starts_at')
                            ->minDate(fn (Get $get): ?string => $get('starts_at')),
                    ]),
                Forms\Components\Section::make()
                    ->hiddenLabel()
                    ->description('')
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Forms\Components\Select::make('priority')
                            ->label(__('tasks-management::fields.tasks.priority'))
                            ->options(PriorityType::class)
                            ->required()
                            ->default(PriorityType::Low),
                        Forms\Components\Select::make('tags')
                            ->multiple()
                            ->placeholder(__('tasks-management::fields.tasks.placeholders.tags'))
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
                        Forms\Components\Select::make('users')
                            ->label(__('tasks-management::fields.tasks.assigned_users'))
                            ->relationship('users', 'name', function ($query) {
                                return self::applyUserQueryModifier($query);
                            })
                            ->preload()
                            ->multiple()
                            ->columnSpanFull(),
                        CustomFields::make()
                            ->columns( 1)
                            ->fieldSpan(1),
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
