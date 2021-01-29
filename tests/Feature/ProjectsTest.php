<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_add_a_project()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->word,
            'description' => $this->faker->paragraph
        ];

        $this->actingAs(User::factory()->create());

        $this->post('api/projects', $attributes);

        $this->assertDatabaseHas('projects', $attributes );

        $this->get('api/projects')->assertSee($attributes['title']);
    }

    public function test_guests_cannot_view_projects()
    {
        $project = Project::factory()->create();

        $this->get($project->path())->assertRedirect('/login');
    }

    public function test_an_authenticated_user_cannot_view_projects_of_others()
    {
        $this->actingAs($user = User::factory()->create());

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);

    }

    public function test_a_project_requires_a_title()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['title' => '', ]);

        $this->post('/api/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_a_description()
    {
        $this->actingAs(User::factory()->create()); 

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/api/projects', $attributes)->assertSessionHasErrors('description');
    }

    public function test_a_project_belongs_to_an_owner()
    {   
        // $this->withoutExceptionHandling();
        $attributes = Project::factory()->raw(['owner_id' => '']);

        $this->post('/api/projects', $attributes)->assertRedirect('login');
    }
}
