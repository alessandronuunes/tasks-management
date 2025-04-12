<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Forms\Components;

use Alessandronuunes\TasksManagement\Models\TaskCustomField;
use Alessandronuunes\TasksManagement\Enums\CustomFieldType;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CustomFields extends Component
{
    protected string $view = 'tasks-management::filament.forms.components.custom-fields';

    public static function make(): static
    {
        return app(static::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema($this->getCustomFieldsSchema());
    }

    protected function getCustomFieldsSchema(): array
    {
        return TaskCustomField::query()
            ->orderBy('sort_order')
            ->get()
            ->map(function (TaskCustomField $field) {
                return match ($field->type) {
                    CustomFieldType::Select->value => $this->makeSelectField($field),
                    CustomFieldType::Text->value => $this->makeTextField($field),
                    default => throw new \InvalidArgumentException(__('tasks-management::messages.custom_fields.invalid_type')),
                };
            })
            ->toArray();
    }
}