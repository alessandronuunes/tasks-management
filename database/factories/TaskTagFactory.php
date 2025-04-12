<?php

namespace Alessandronuunes\TasksManagement\Database\Factories;

use Alessandronuunes\TasksManagement\Models\TaskTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskTagFactory extends Factory
{
    protected $model = TaskTag::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'color' => $this->faker->hexColor(),
        ];
    }
}