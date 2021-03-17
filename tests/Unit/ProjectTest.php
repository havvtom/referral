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

    public function test_a_project_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $userToInvite = User::factory()->create();

        $this->actingAs( $project->owner )->post("api/referrals/{$project->id}", ['email' => $userToInvite->email]);

        $this->assertTrue($project->members->contains( $userToInvite ));
    }

    public function test_non_owners_cannot_invite_users()
    {
        $project = Project::factory()->create();

        $project->invite( $user = User::factory()->create() );

         $response = $this->actingAs( $user )->post("api/referrals/{$project->id}", ['email' => 'email@example.email.com']);

        $response->assertStatus(403);
    }

    public function test_unauthorized_users_cannot_delete_projects()
    {
        $project = Project::factory()->create();

        $project->invite( $user = User::factory()->create() );

        $this->actingAs( $user );

        $this->delete($project->path())->assertStatus(403);

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
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
