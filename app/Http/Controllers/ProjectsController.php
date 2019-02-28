<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        dd(new Controller);
        $projects = \App\Project::all();

        return view('projects.index', compact('projects'));
    }

    public function store()
    {   
        request()->validate(['title' => 'required', 'description' => 'required']);

        \App\Project::create(request(['title','description']));

        return redirect('/projects');
    }

    public function show()
    {

    }
}
