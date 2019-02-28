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
    public function a_project_requires_a_title()
    {
        $this->post('/projects', [])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->post('/projects', [])->assertSessionHasErrors('description');
    }
}
