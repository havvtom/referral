<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectModelObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        Activity::create([
                'project_id' => $project->id,
                'description' => 'created',
                'user_id' => $this->activityOwner($project)->id
            ]);
    }

    /**
     * Handle the Project "updated" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        Activity::create([
                'user_id' => $this->activityOwner($project)->id,
                'project_id' => $project->id,
                'description' => 'updated',
                'changes' => [
                    'before' => array_diff($project->old, $project->getAttributes()),
                    'after' => $project->getChanges()
                ]
            ]);
    }

    /**
     * Handle the Project "deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function activityOwner(Project $project)
    {
        if(auth()->check()){
            return auth()->user();
        }

        return $project->owner;
    }

    public function deleted(Project $project)
    {
        
    }

    public function updating(Project $project)
    {
        $project->old = $project->getOriginal();
    }

    /**
     * Handle the Project "restored" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
