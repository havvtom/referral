<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
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

        $project = Project::factory()->create(['owner_id' => auth()->user()->id]);

        $this->post($project->path(). '/tasks', ['body' => 'Hey wena']);

        $this->assertEquals(1, $project->tasks->count());
    }

    public function test_a_task_should_have_a_body()
    {

        $this->signIn();

        $project = Project::factory()->create(['owner_id' => auth()->user()->id]);

        $this->post( "api/projects/{$project->id}/tasks", ['body' => ''])->assertSessionHasErrors('body');
    }

    public function test_only_the_project_owner_can_add_tasks()
    {

        $this->signIn();

        $project = Project::factory()->create();

        $this->post( "api/projects/{$project->id}/tasks", ['body' => '']);

        $this->assertDatabaseMissing('tasks', ['body' => 'My new task']);
    }

    public function test_a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->signIn($user = User::factory()->create());

        $project = auth()->user()->projects()->create(Project::factory()->raw());
        
        $task = $project->addTask( ['body' => 'changed']);
        
        $this->patch($project->path().'/tasks/'.$task->id, ['body' => 'Changed', 'completed' => true]);
                            
        $this->assertDatabaseHas('tasks', [
                                'body' => 'Changed',
                                'completed' => true
                            ]);
    }

    public function test_only_project_owner_can_update_a_task()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $task = $project->addTask(['body' => 'new task']);

        $this->patch($project->path().'/tasks/'.$task->id, ['body' => 'task test'])
            ->assertStatus(403);

            $this->assertDatabaseMissing('tasks', ['body' => 'task test']);
    }
}
