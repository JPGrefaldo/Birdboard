@extends('layouts.app')
@section('content')
    <div class="flex items-center mb-3">
        <a href="/projects/create">Create New Project</a>
    </div>

    <div class="flex">
        @forelse ($projects as $project)
            <div class="bg-white mr-4 p-5 rounded shadow w-1/3" style="height: 200px">
                <h3 class="font-normal text-xl mb-6">{{ $project->title }}</h3>

                <div>{{ str_limit($project->description) }}</div>
            </div>
        @empty
            <div>No Projects yet.</div>
        @endforelse
    </div>
@endsection