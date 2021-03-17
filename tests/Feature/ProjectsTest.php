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
            'description' => $this->faker->paragraph,
            'notes' => 'good to see you'
        ];

        $this->actingAs(User::factory()->create());

        $this->post('api/projects', $attributes);

        $project = Project::where($attributes)->first();

        $this->assertDatabaseHas('projects', $attributes );

        $this->get('api/projects')->assertSee($attributes['title']);
    }

    public function test_a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->patch($project->path(), ['title' => 'Title changed', 'notes' => 'Notes changed', 'description' => 'described' ]);

        $this->assertDatabaseHas('projects', ['notes' => 'Notes changed']);
    }

    public function test_a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = auth()->user()->projects()->create( Project::factory()->raw() );

        $this->delete($project->path());

        $this->assertDatabaseMissing('projects', $project->getAttributes());
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

        $this->get("{$project->path()}")->assertStatus(403);

    }

    public function test_an_authenticated_user_cannot_update_projects_of_others()
    {
        $this->actingAs($user = User::factory()->create());

        $project = Project::factory()->create();

        $this->patch("{$project->path()}", ['title' => 'To be updated', 'description' => 'Changed'])->assertStatus(403);

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
