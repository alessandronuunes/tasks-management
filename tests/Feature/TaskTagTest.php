<?php

use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Models\TaskTag;

test('can create a tag', function () {
    $tag = TaskTag::factory()->create();

    expect($tag)->toBeInstanceOf(TaskTag::class)
        ->and($tag->name)->not->toBeEmpty()
        ->and($tag->color)->not->toBeEmpty();
});

test('can assign tags to a task', function () {
    $task = Task::factory()->create();
    $tags = TaskTag::factory(3)->create();

    $task->tags()->attach($tags);

    expect($task->tags)->toHaveCount(3)
        ->and($task->tags->first())->toBeInstanceOf(TaskTag::class);
});

test('can sync tags on a task', function () {
    $task = Task::factory()->create();
    $initialTags = TaskTag::factory(2)->create();
    $newTags = TaskTag::factory(3)->create();

    $task->tags()->attach($initialTags);
    expect($task->tags)->toHaveCount(2);

    $task->tags()->sync($newTags);
    $task->refresh();
    
    expect($task->tags)->toHaveCount(3)
        ->and($task->tags->pluck('id'))->toEqual($newTags->pluck('id'));
});

test('deleting a tag detaches it from tasks', function () {
    $task = Task::factory()->create();
    $tag = TaskTag::factory()->create();

    $task->tags()->attach($tag);
    expect($task->tags)->toHaveCount(1);

    $tag->delete();
    $task->refresh();

    expect($task->tags)->toHaveCount(0);
});