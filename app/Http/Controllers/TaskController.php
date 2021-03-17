<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
    	$this->authorize('update', $project);

    	$request->validate([
    		'body' => 'required'
    	]);

    	$project->addTask($request->only('body'));

    }

    public function update(Project $project, Task $task, Request $request)
    {
    	$this->authorize('update', $project);
    	
    	if( $request->has('completed') ){

            return $task->complete( $request->body );
        }

        $task->incomplete( $request->body );
    }

    public function destroy(Project $project, Task $task, Request $request)
    {
        $this->authorize('update', $project);

        $task->delete();
    }
}
