<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
    	$request->validate([
    		'body' => 'required'
    	]);
    	
    	$task = $project->addTask($request->only('body'));
    }
}
