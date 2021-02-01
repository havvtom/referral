<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = Project::factory()->create();

        $this->post($project->path(). '/tasks', ['body' => 'Hey wena']);

        $this->assertEquals(1, $project->tasks->count());
    }

    public function test_a_task_should_have_a_body()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $project = Project::factory()->create();

        $this->post( "api/projects/{$project->id}/tasks", ['body' => ''])->assertSessionHasErrors('body');
    }
}
