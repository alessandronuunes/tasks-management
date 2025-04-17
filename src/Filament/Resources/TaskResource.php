<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Traits\HasResourceConfig;
use Alessandronuunes\TasksManagement\Traits\AuthorizedUsersTrait;
use Alessandronuunes\TasksManagement\Traits\UserQueryModifierTrait;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;
use Alessandronuunes\TasksManagement\Filament\Forms\Components\CustomFields;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\RelationManagers\CommentsRelationManager;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\RelationManagers\AuditsRelationManager;

class TaskResource extends Resource
{
    use HasResourceConfig;
    use AuthorizedUsersTrait;
    use UserQueryModifierTrait;
    
    protected static ?string $model = Task::class;
    public static function getLabel(): string
    {
        return __('tasks-management::labels.tasks.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('tasks-management::labels.tasks.plural');
    }
    public static function canViewAny(): bool
    {
        return self::isAuthorized();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('tasks-management::fields.common.name'))
                            ->placeholder(__('tasks-management::fields.tasks.placeholders.name'))
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label(__('tasks-management::fields.common.description'))
                            ->placeholder(__('tasks-management::fields.tasks.placeholders.description'))
                            ->disableToolbarButtons(['blockquote', 'codeBlock'])
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('attachments')
                            ->label(__('tasks-management::fields.tasks.attachments'))
                            ->multiple()
                            ->columnSpanFull()
                            ->directory('task-attachments')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->maxSize(10240)
                    ]),
                    
                    CustomFields::make()
                        ->columns( 12)
                        ->fieldSpan(6),
                    Forms\Components\Grid::make()
                    ->columns(12)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('tasks-management::fields.tasks.status'))
                            ->options(TaskStatus::class)
                            ->required()
                            ->default(TaskStatus::Pending)
                            ->columnSpan(6),
                        Forms\Components\Select::make('priority')
                            ->label(__('tasks-management::fields.tasks.priority'))
                            ->options(PriorityType::class)
                            ->required()
                            ->default(PriorityType::Low)
                            ->columnSpan(6),
                    ]),
                Forms\Components\Grid::make()
                    ->columns(12)
                    ->schema([
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
                            ->columnSpan(6)
                            ->preload(),
                        Forms\Components\Select::make('users')
                            ->label(__('tasks-management::fields.tasks.users'))
                            ->relationship('users', 'name', function ($query) {
                                return self::applyUserQueryModifier($query);
                            })
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpan(6),
                
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tasks-management::fields.common.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('tasks-management::fields.tasks.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('tasks-management::fields.tasks.priority'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tasks-management::fields.common.created_at'))
                    ->dateTime(),
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
        $relations = [
            CommentsRelationManager::class,
        ];
        
        // Add the audits relation manager if logging is enabled
        if (config('tasks-management.logging.enabled', false)) {
            $relations[] = AuditsRelationManager::class;
        }
        
        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    // Remova o método mutateFormDataBeforeSave que foi adicionado anteriormente
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['custom_fields'])) {
            $this->customFields = $data['custom_fields'];
        }
        return $data;
    }
}
