<?php

namespace Alessandronuunes\TasksManagement\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class OptionsArrayCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (!$value) {
            return [];
        }
        
        $options = json_decode($value, true);
        return collect($options)->mapWithKeys(function ($item) {
            return [$item['value'] => $item['label']];
        })->toArray();
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (!is_array($value)) {
            return null;
        }

        return json_encode(array_map(function ($item) {
            if (is_string($item)) {
                return [
                    'label' => $item,
                    'value' => Str::slug($item, '_'),
                    'order' => 0,
                ];
            }
            
            return [
                'label' => $item['label'] ?? '',
                'value' => $item['value'] ?? '',
                'order' => $item['order'] ?? 0,
            ];
        }, $value));
    }
}