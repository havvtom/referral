<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
    	'completed' => 'boolean'
    ];

    protected $touches = [
        'project'
    ];

    protected $guarded = [];

    public $old = [];

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function path()
    {
    	return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function complete( $body )
    {
        $this->update([ 'completed' => true, 'body' => $body ]);

        $this->recordActivity();

    }

    public function incomplete( $body )
    {
        $this->update([ 'completed' => false, 'body' => $body ]);

        $this->recordActivity();

    }

    public function recordActivity()
    {
        $this->activity()->create([
            'user_id' => $this->activityOwner()->id,
            'description' => 'completed_task',
            'project_id' => $this->project_id,
            'changes' => [
                    'before' => array_diff($this->old, $this->getAttributes()),
                    'after' => $this->getChanges()
                ]
        ]);
    }

    public function activityOwner()
    {
        if(auth()->check()){
            return auth()->user();
        }

        return $this->project->owner;
    }
}
