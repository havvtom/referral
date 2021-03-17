<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_task_belongs_to_a_project()
    {
        $task = Task::factory()->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    public function test_a_task_has_a_path()
    {

        $task = Task::factory()->create();

        $this->assertEquals("/projects/{$task->project->id}/tasks/{$task->id}", $task->path());
    }

    public function test_a_task_can_be_completed()
    {
        $task = Task::factory()->create();

        $this->assertFalse($task->completed);

        $task->complete('complete');

        $this->assertTrue($task->fresh()->completed);
    }

    public function test_a_task_can_be_incompleted()
    {
        $task = Task::factory()->create();

        $task->complete('complete');

        $this->assertTrue($task->completed);

        $task->incomplete('incomplete');

        $this->assertFalse($task->completed);
    }
}
