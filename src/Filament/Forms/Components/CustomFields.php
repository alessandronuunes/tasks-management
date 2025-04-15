<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Forms\Components;

use Alessandronuunes\TasksManagement\Models\TaskCustomField;
use Alessandronuunes\TasksManagement\Enums\CustomFieldType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CustomFields extends Grid
{
    protected string $view = 'filament-forms::components.grid';
    
    protected int $fieldColumnSpan = 4;
    
    protected bool $isConfigured = false;

    public static function make(array | int | string | null $columns = 2): static
    {
        $static = app(static::class, ['columns' => $columns]);
        $static->configure();

        return $static;
    }

    public function fieldSpan(int $span): static
    {
        $this->fieldColumnSpan = $span;
        
        // Se já estiver configurado, atualize o schema
        if ($this->isConfigured) {
            $this->schema($this->getCustomFieldsSchema());
        }
        
        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Não configuramos o schema aqui, apenas marcamos como configurado
        $this->isConfigured = true;
    }
    
    // Sobrescrever o método mount para configurar o schema no momento certo
    public function mount(): void
    {
        // Configurar o schema antes de montar o componente
        $this->schema($this->getCustomFieldsSchema());
        
        parent::mount();
    }

    protected function getCustomFieldsSchema(): array
    {
        return TaskCustomField::query()
            ->orderBy('sort_order')
            ->get()
            ->map(function (TaskCustomField $field) {
                return match ($field->type) {
                    CustomFieldType::Select => $this->makeSelectField($field),
                    CustomFieldType::Text => $this->makeTextField($field),
                    default => throw new \InvalidArgumentException(__('tasks-management::messages.custom_fields.invalid_type')),
                };
            })
            ->toArray();
    }

    protected function makeSelectField(TaskCustomField $field): Select
    {
        return Select::make("custom_fields.{$field->code}")
            ->label($field->name)
            ->options($field->options)
            ->required($field->is_required)
            ->placeholder($field->placeholder)
            ->helperText($field->help_text)
            ->hint($field->hint)
            ->columnSpan($this->fieldColumnSpan);
    }

    protected function makeTextField(TaskCustomField $field): TextInput
    {
        return TextInput::make("custom_fields.{$field->code}")
            ->label($field->name)
            ->required($field->is_required)
            ->placeholder($field->placeholder)
            ->helperText($field->help_text)
            ->hint($field->hint)
            ->columnSpan($this->fieldColumnSpan);
    }
}