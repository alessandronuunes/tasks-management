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
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('tasks-management::fields.custom_fields.settings'))
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('tasks-management::fields.common.name'))
                                    ->required()
                                    ->live()
                                    ->columnSpan(6)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }
                                        $set('code', Str::slug($state, '_'));
                                    }),
                                
                                TextInput::make('code')
                                    ->label(__('tasks-management::fields.custom_fields.code'))
                                    ->required()
                                    ->columnSpan(6)
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                                
                                Select::make('type')
                                    ->label(__('tasks-management::fields.custom_fields.type'))
                                    ->options(CustomFieldType::class)
                                    ->default(CustomFieldType::Text)
                                    ->live()
                                    ->columnSpan(6)
                                    ->required(),
                                
                                TextInput::make('sort_order')
                                    ->columnSpan(6)
                                    ->label(__('tasks-management::fields.custom_fields.sort_order'))
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('placeholder')
                                    ->columnSpan(4)
                                    ->live()
                                    ->label(__('tasks-management::fields.custom_fields.placeholder')),

                                TextInput::make('help_text')
                                    ->columnSpan(4)
                                    ->live()
                                    ->label(__('tasks-management::fields.custom_fields.help_text')),

                                TextInput::make('hint')
                                    ->columnSpan(4)
                                    ->live()
                                    ->label(__('tasks-management::fields.custom_fields.hint')),

                                Forms\Components\Checkbox::make('is_required')
                                    ->columnSpanFull()
                                    ->live()
                                    ->label(__('tasks-management::fields.custom_fields.is_required')),

                                Forms\Components\Fieldset::make('Options')
                                    ->label(__('tasks-management::fields.custom_fields.options'))
                                    ->visible(function (Forms\Get $get): bool {
                                        $type = $get('type');
                                        return $type instanceof CustomFieldType 
                                            ? $type === CustomFieldType::Select 
                                            : $type === CustomFieldType::Select->value;
                                    })
                                    ->schema([
                                        Forms\Components\Repeater::make('options')
                                            ->simple(
                                                TextInput::make('label')
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                        if (!is_string($state)) {
                                                            return;
                                                        }
                                                        $set('value', Str::slug($state, '_'));
                                                    })
                                            )
                                            ->live()
                                            ->columnSpanFull()
                                            ->hiddenLabel()
                                            ->defaultItems(1)
                                            ->addActionLabel('Adicionar Opção')
                                            ->reorderable(),
                                    ]),

                            ])->columns(12),

                        Forms\Components\Tabs\Tab::make(__('tasks-management::fields.custom_fields.preview'))
                            ->schema([
                                TextInput::make('preview')
                                    ->label(fn (Forms\Get $get) => $get('name') ?: __('tasks-management::fields.custom_fields.preview_label'))
                                    ->placeholder(fn (Forms\Get $get) => $get('placeholder'))
                                    ->helperText(fn (Forms\Get $get) => $get('help_text'))
                                    ->hint(fn (Forms\Get $get) => $get('hint'))
                                    //->required(fn (Forms\Get $get): bool => (bool) $get('is_required'))
                                    ->visible(fn (Forms\Get $get): bool => 
                                         $get('type') === CustomFieldType::Text->value || $get('type') === CustomFieldType::Text
                                    )
                                    //->disabled()
                                    ->live(onBlur: true)
                                    ->columnSpanFull()
                                    ->dehydrated(false),
                                
                                Select::make('preview_select')
                                    ->columnSpanFull()
                                    ->label(fn (Forms\Get $get) => $get('name') ?: __('tasks-management::fields.custom_fields.preview_label'))
                                    ->options(function (Forms\Get $get) {
                                        $options = $get('options') ?? [];
                                        return collect($options)
                                            ->filter(fn ($option) => !empty($option['label']))
                                            ->mapWithKeys(fn ($option) => [
                                                $option['label'] => $option['label']
                                            ])
                                            ->toArray();
                                    })
                                    //->disabled()
                                    ->helperText(fn (Forms\Get $get) => $get('help_text'))
                                    ->hint(fn (Forms\Get $get) => $get('hint'))
                                    //->required(fn (Forms\Get $get): bool => (bool) $get('is_required'))
                                    ->visible(fn (Forms\Get $get): bool => $get('type') === CustomFieldType::Select->value || $get('type') === CustomFieldType::Select)
                                    ->live(onBlur: true)
                                    ->dehydrated(false)
                            ])->columns(12),
                    ])->columnSpanFull(),
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
                Tables\Actions\EditAction::make()
                    ->modalWidth('2xl'),
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