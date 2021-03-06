<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_user_has_projects()
    {
    	$this->actingAs($user = User::factory()->create());

    	$project = Project::factory(2)->create(['owner_id' => $user->id]);

    	$this->assertEquals(2, $user->projects->count());
    }

    public function test_a_user_has_accesible_projects()
    {
        $user = User::factory()->create();

        $user->projects()->create( Project::factory()->raw() );

        $project = Project::factory()->create();

        $project->invite( $user );

        $this->assertCount(2, $user->accessibleProjects());

    }
}
