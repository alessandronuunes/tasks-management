<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Alessandronuunes\TasksManagement\Models\TaskCustomField;
use Alessandronuunes\TasksManagement\Traits\HasResourceConfig;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource\Pages;
use Alessandronuunes\TasksManagement\Enums\CustomFieldType;

class TaskCustomFieldResource extends Resource
{
    use HasResourceConfig;
    protected static ?string $model = TaskCustomField::class;

    public static function getLabel(): string
    {
        return __('tasks-management::labels.custom_fields.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('tasks-management::labels.custom_fields.plural');
    }
    
    public static function getNavigationParentItem(): ?string
    {
        return __('tasks-management::navigation.parent.custom_fields');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('tasks-management::fields.common.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        
                        $set('code', Str::slug($state, '_'));
                    }),
                    
                TextInput::make('code')
                    ->label(__('tasks-management::fields.custom_fields.code'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                    
                Select::make('type')
                    ->label(__('tasks-management::fields.custom_fields.type'))
                    ->options(CustomFieldType::class)
                    ->enum(CustomFieldType::class)
                    ->required(),
                
                Select::make('options')
                    ->label(__('tasks-management::fields.custom_fields.options'))
                    ->multiple()
                    ->visible(fn (Forms\Get $get): bool => $get('type') === CustomFieldType::Select->value),
                    
                Toggle::make('is_required')
                    ->label(__('tasks-management::fields.custom_fields.is_required')),
                    
                TextInput::make('sort_order')
                    ->label(__('tasks-management::fields.custom_fields.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tasks-management::fields.common.name'))
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('code')
                    ->label(__('tasks-management::fields.custom_fields.code'))
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label(__('tasks-management::fields.custom_fields.type'))
                    ->badge(),
                    
                Tables\Columns\IconColumn::make('is_required')
                    ->label(__('tasks-management::fields.custom_fields.is_required'))
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('tasks-management::fields.custom_fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskCustomFields::route('/'),
            //'create' => Pages\CreateTaskCustomField::route('/create'),
            //'edit' => Pages\EditTaskCustomField::route('/{record}/edit'),
        ];
    }
}