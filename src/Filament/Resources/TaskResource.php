<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;

use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Enums\TaskType;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use W2ca\FilamentToggleGroup\Forms\Components\ToggleGroup;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): ?string
    {
        return config('tasks-management.navigation.group', 'Tasks');
    }

    public static function getNavigationSort(): ?int
    {
        return config('tasks-management.navigation.sort', 30);
    }

    public static function getModel(): string
    {
        return config('tasks-management.models.task');
    }

    public static function getLabel(): string
    {
        return __('tasks-management::tasks.task');
    }

    public static function getPluralLabel(): string
    {
        return __('tasks-management::tasks.tasks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->columnSpanFull()
                    ->extraAttributes(['class' => '!ring-0 !shadow-none'])
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('basic')
                            ->label(__('tasks-management::tasks.basic'))
                            ->columns(12)
                            ->schema(static::getFieldsTasksBasic()),
                        Forms\Components\Tabs\Tab::make('advanced')
                            ->label(__('tasks-management::tasks.advanced'))
                            ->schema(static::getFieldsTasksAdvanced()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordClasses(function ($record) {
                if (! static::verifyReadAt($record)) {
                    return 'border-l-2 !border-l-indigo-600 !dark:border-l-indigo-300';
                }
            })
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('Título'))
                        ->icon(fn (Task $record) => ! static::verifyReadAt($record) ? 'heroicon-o-finger-print' : null)
                        ->iconColor('indigo')
                        ->sortable()
                        ->searchable()
                        ->limit(),
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('status')
                            ->tooltip(__('Status'))
                            ->sortable()
                            ->badge()
                            ->searchable()
                            ->grow(false),
                        Tables\Columns\TextColumn::make('priority')
                            ->tooltip(__('Prioridade'))
                            ->badge()
                            ->sortable()
                            ->grow(false),
                        Tables\Columns\ImageColumn::make('users')
                            ->tooltip(__('Usuários'))
                            ->getStateUsing(fn (Task $record) => $record->users->map(fn ($user) => $user->getDefaultProfilePhotoUrlAttribute())->toArray())
                            ->circular()
                            ->stacked()
                            ->limit()
                            ->limitedRemainingText(isSeparate: true)
                            ->size(24)
                            ->grow(false),
                        Tables\Columns\TextColumn::make('comments_count')
                            ->tooltip(__('Comentários'))
                            ->counts('comments')
                            ->badge()
                            ->tooltip(__('Comentários'))
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->grow(false),
                        Tables\Columns\TextColumn::make('ends_at')
                            ->tooltip(__('Prazo'))
                            ->formatStateUsing(function (?string $state) {
                                $diff = Carbon::parse($state)->diffInDays(now(), true);

                                if ($diff > 3) {
                                    return local_date($state, 'd M Y', true);
                                }

                                return Carbon::parse($state)->diffForHumans();
                            })
                            ->sortable()
                            ->badge()
                            ->icon('heroicon-o-clock')
                            ->toggleable()
                            ->grow(false),
                    ]),
                ])

                ->space(3),
            ])
            ->extremePaginationLinks()
            ->groups([
                Tables\Grouping\Group::make('status.name')
                    ->label('Status'),
                Tables\Grouping\Group::make('priority')
                    ->label('Prioridade'),
            ])
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getFieldsTasksBasic(): array
    {
        return [
            Forms\Components\Grid::make()
                ->columnSpanFull()
                ->schema([
                    ToggleGroup::make('type')
                        ->hiddenLabel()
                        ->options(TaskType::class)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('name')
                        ->label(__('tasks-management::tasks.name'))
                        ->placeholder(fn (Get $get) => TaskType::tryFrom($get('type') ?? '')?->getLabel())
                        ->columnSpanFull()
                        ->required(),
                    Forms\Components\RichEditor::make('description')
                        ->label(__('tasks-management::tasks.description'))
                        ->columnSpanFull(),
                ]),
            Forms\Components\Grid::make()
                ->columns()
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label(__('tasks-management::tasks.status'))
                        ->options(TaskStatus::class)
                        ->enum(TaskStatus::class)
                        ->required()
                        ->default(TaskStatus::Pending),
                    Forms\Components\Select::make('priority')
                        ->label(__('tasks-management::tasks.priority'))
                        ->options(PriorityType::class)
                        ->required()
                        ->default(PriorityType::Low),
                ]),
        ];
    }

    public static function getFieldsTasksAdvanced(): array
    {
        $morphableTypes = config('tasks-management.morphable_types', []);

        $schema = [
            Forms\Components\Select::make('users')
                ->relationship('users', 'name')
                ->label(__('tasks-management::tasks.assigned_to'))
                ->multiple()
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('starts_at')
                ->label(__('tasks-management::tasks.starts_at'))
                ->live()
                ->displayFormat('d M Y H:i')
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('ends_at')
                ->label(__('tasks-management::tasks.ends_at'))
                ->live()
                ->displayFormat('d M Y H:i')
                ->after('starts_at')
                ->columnSpanFull(),
        ];

        if (! empty($morphableTypes)) {
            array_unshift(
                $schema,
                Toggle::make('is_type')
                    ->label(__('tasks-management::tasks.relate_to'))
                    ->live()
                    ->afterStateUpdated(fn (Set $set, $state) => ! $state ? $set('taskable_type', null) : '')
                    ->columnSpanFull(),
                ToggleButtons::make('taskable_type')
                    ->hidden(fn (Get $get) => ! $get('is_type'))
                    ->hiddenLabel()
                    ->live()
                    ->options($morphableTypes)
                    ->afterStateUpdated(fn (Set $set) => $set('taskable_id', null))
            );

            foreach ($morphableTypes as $type => $label) {
                $schema[] = Forms\Components\Select::make('taskable_id')
                    ->hidden(fn (Get $get) => $get('taskable_type') !== $type)
                    ->label($label)
                    ->columnSpanFull()
                    ->searchable();
            }
        }

        return [
            Forms\Components\Grid::make()
                ->columns()
                ->schema($schema),
        ];
    }

    public static function verifyReadAt($record): bool
    {
        if (! $record || ! method_exists($record, 'users')) {
            return true;
        }

        if (! isset($record->user_id) || $record->user_id === auth()->id()) {
            return true;
        }

        $readAt = $record->users()->where('users.id', auth()->id())->value('read_at');

        return ! empty($readAt);
    }
}
