<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_creating_a_project_generates_an_activity()
    {
        $project = Project::factory()->create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('created', $project->activity[0]->description);
    }

    public function test_updating_a_project_generates_an_activity()
    {
        $project = Project::factory()->create();

        $originalTitle = $project->title;

        $project->update(['title' => 'Title changed']);

        $this->assertEquals('updated', $project->activity->last()->description);

        $expected = [
            'before' => ['title' => $originalTitle],
            'after' => ['title' => 'Title changed']
        ];

        $this->assertEquals($expected, $project->activity->last()->changes);
    }

    public function test_adding_a_task_records_activity()
    {
        $project = Project::factory()->create();

        $project->addTask(['body' => 'Add task']);

        $this->assertCount(2, $project->activity);

        $this->assertEquals('created_task', $project->activity->last()->description);

        $this->assertInstanceOf( Task::class, $project->activity->last()->subject );
    }

    public function test_completing_a_task_records_activity()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create());

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $task = Task::factory()->create(['project_id' => $project->id]);

        $this->patch("api{$task->path()}", ['body' => 'Add task', 'completed' => true]);

        $this->assertCount(3, $project->activity);

        // dd($project->activity->last()->description);

        $this->assertEquals('completed_task', $project->activity->last()->description);

        $this->assertInstanceOf( Task::class, $project->activity->last()->subject );
    }

    public function test_incompleting_a_task_records_activity()
    {
        $this->withoutExceptionHandling();

        $this->actingAs( $user = User::factory()->create() );

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $task = $project->tasks()->create( Task::factory()->raw() );

        $this->patch("api{$task->path()}", ['body' => 'Add task', 'completed' => true]);       

        $this->assertCount(3, $project->activity);

        $this->patch("api{$task->path()}", ['body' => 'Add task']);

        $this->assertCount(4, $project->fresh()->activity);
        
    }

    public function test_deleting_a_task_records_activity()
    {
        $this->withoutExceptionHandling();
        
        $this->actingAs( $user = User::factory()->create() );

        $project = $user->projects()->create( Project::factory()->raw() );

        $task = $project->tasks()->create( Task::factory()->raw() );

        // $task->delete();

        $this->delete("api{$task->path()}");

        $this->assertCount(3, $project->fresh()->activity);
    }

}
