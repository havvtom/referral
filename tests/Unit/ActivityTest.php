<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_a_user()
    {
    	$this->actingAs( $user = User::factory()->create() );
    	
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->activity->first()->user);
    }
}
