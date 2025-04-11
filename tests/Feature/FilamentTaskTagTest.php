<?php

use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource;
use Alessandronuunes\TasksManagement\Models\TaskTag;
use function Pest\Livewire\livewire;

test('can render task tag list page', function () {
    $this->get(TaskTagResource::getUrl('index'))->assertSuccessful();
});

test('can create task tag', function () {
    $newData = [
        'name' => 'Important',
        'color' => '#ff0000',
    ];

    livewire(TaskTagResource\Pages\CreateTaskTag::class)
        ->fillForm($newData)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('task_tags', $newData);
});

test('can edit task tag', function () {
    $tag = TaskTag::factory()->create();
    $newData = [
        'name' => 'Updated Tag',
        'color' => '#00ff00',
    ];

    livewire(TaskTagResource\Pages\EditTaskTag::class, [
        'record' => $tag->getKey(),
    ])
        ->fillForm($newData)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('task_tags', $newData);
});

test('can delete task tag', function () {
    $tag = TaskTag::factory()->create();

    livewire(TaskTagResource\Pages\ListTaskTags::class)
        ->callTableAction('delete', $tag);

    $this->assertDatabaseMissing('task_tags', [
        'id' => $tag->id,
    ]);
});