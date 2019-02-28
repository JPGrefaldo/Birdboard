<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_project()
    {
        $this->withoutExceptionHandling();
        
        $this->actingAs(factory('App\User')->create());

        $attriubtes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attriubtes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attriubtes);

        $this->get('/projects')->assertSee($attriubtes['title']);
    }

    /** @test */
    public function a_user_can_view_thier_project()
    {   
        $this->be(factory('App\User')->create());
        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
                ->assertSee($project->title)->assertSee($project->description);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attriubtes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attriubtes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAs(factory('App\User')->create());

        $attriubtes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attriubtes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function guest_cannot_create_projects()
    {
        $attriubtes = factory('App\Project')->raw();

        $this->post('/projects', $attriubtes)->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_a_single_projects()
    {
        $project = factory('App\Project')->create();
        
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->be(factory('App\User')->create());

        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }
}
