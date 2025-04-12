@php
    $hasInlineLabel = $hasInlineLabel();
    $id = $getId();
    $isDisabled = $isDisabled();
    $isInline = $isInline();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>

    <x-filament::button.group
        :attributes="
    \Filament\Support\prepare_inherited_attributes($attributes)
        ->merge($getExtraAttributes(), escape: false)
        ->class([
            'w-full gap-x-1 p-0.5 bg-white dark:bg-gray-900'
        ])
"
    >
        @foreach ($getOptions() as $value => $label)
            @php
                $shouldOptionBeDisabled = $isDisabled || $isOptionDisabled($value, $label);
                $isSelected = $value === $getState();
            @endphp

            <button
                type="button"
                wire:click="$set('{{ $statePath }}', '{{ $value === $getState() ? '' : $value }}')"
                @disabled($shouldOptionBeDisabled)
                @class([
                    'flex items-center justify-center gap-1.5 px-4 py-1.5 text-sm font-medium rounded-md transition-colors',
                    'bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400' => $isSelected,
                    'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' => !$isSelected,
                    'opacity-50 cursor-not-allowed' => $shouldOptionBeDisabled,
                ])
            >
                @if($getIcon($value))
                    <x-filament::icon
                        class="fi-btn-icon transition duration-75 h-5 w-5"
                        :icon="$getIcon($value)"
                    />
                @endif

                {{ $label }}
            </button>
        @endforeach
    </x-filament::button.group>
</x-dynamic-component>
