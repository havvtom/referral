<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_project_can_invite_users()
    {
        $this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $project->invite( $newUser = User::factory()->create() );

        $this->signIn( $newUser );

        $this->post("api/projects/{$project->id}/tasks", $attributes = ['body' => 'New task']);

        $this->assertDatabaseHas('tasks', $attributes );
    }
}
