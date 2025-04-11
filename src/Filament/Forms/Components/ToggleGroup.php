<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Contracts;
use Filament\Forms\Components\Field;

class ToggleGroup extends Field implements Contracts\CanDisableOptions
{
    use Concerns\CanDisableOptions;
    use Concerns\CanDisableOptionsWhenSelectedInSiblingRepeaterItems;
    use Concerns\CanFixIndistinctState;
    use Concerns\HasColors;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasIcons;
    use Concerns\HasNestedRecursiveValidationRules;
    use Concerns\HasOptions;

    protected bool | Closure $isInline = false;

    protected string $view = 'tasks-management::forms.components.toggle-group';

    public function inline(bool | Closure $condition = true): static
    {
        $this->isInline = $condition;

        return $this;
    }

    public function isInline(): bool
    {
        return (bool) $this->evaluate($this->isInline);
    }
}
