<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_guest_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_project()
    {
        $this->withoutExceptionHandling();
        
        $this->signIn();

        $this->get('projects/create')->assertStatus(200);

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
        $this->signIn();
        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
                ->assertSee($project->title)->assertSee($project->description);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attriubtes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attriubtes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attriubtes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attriubtes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }
}
