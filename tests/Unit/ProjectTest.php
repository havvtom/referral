<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_a_project_has_a_path()
    {
    	$project = Project::factory()->create();

    	$this->assertEquals($project->path(), '/api/projects/'.$project->id);
    }

    public function test_a_project_belongs_to_an_owner()
    {
    	$this->actingAs($user = User::factory()->create());

    	$project = Project::factory()->create();

    	$this->assertInstanceOf(User::class, $project->owner);
    }

    public function test_a_project_can_add_a_task()
    {
        $project = Project::factory()->create();

        $task = $project->addTask(['body' => 'Test test body']);

        $this->assertCount(1, $project->tasks);

        $this->assertTrue($project->tasks->contains($task));
    }
}
