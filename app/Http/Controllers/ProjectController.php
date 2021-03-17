<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectResourceCollection;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
	public function __construct()
	{
		// $this->middleware(['auth'])->only('store');
	}

    public function index(Request $request)
    {
    	return new ProjectResourceCollection($request->user()->accessibleProjects());	
    }

    public function show(Project $project)
    {
    	$this->authorize('update', $project);
    	
    	return new ProjectResource($project);
    }

    public function store(ProjectFormRequest $request)
    {	
    	$project = $request->user()->projects()->create($request->only('title', 'description', 'notes'));

    	return new ProjectResource($project);

    }

    public function update(Project $project, ProjectFormRequest $request)
    {
        $this->authorize('update', $project);

        $project->update($request->only('body', 'description', 'notes'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('create', $project);

        $project->delete();
    }
}
