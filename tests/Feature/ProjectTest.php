<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_project()
    {
        $this->withoutExceptionHandling();

        $attriubtes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attriubtes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attriubtes);

        $this->get('/projects')->assertSee($attriubtes['title']);
    }

    /** @test */
    public function a_user_can_view_a_project()
    {   
        $this->withoutExceptionHandling();
        
        $project = factory('App\Project')->create();

        $this->get($project->path())
                ->assertSee($project->title)->assertSee($project->description);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $attriubtes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attriubtes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $attriubtes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attriubtes)->assertSessionHasErrors('description');
    }
}
