<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Tests\Unit;

use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_task()
    {
        $task = Task::create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Test Task',
        ]);
    }

    /** @test */
    public function it_can_have_comments()
    {
        $task = Task::create([
            'name' => 'Test Task',
            'status' => 'pending',
        ]);

        $task->comments()->create([
            'content' => 'Test Comment',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $task->comments);
    }
}
