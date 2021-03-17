<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $old = [];

    public $timestamps = false;

    public function path()
    {
    	return "/api/projects/{$this->id}";
    }

    public function owner()
    {
    	return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function addTask( $body )
    {
        return $this->tasks()->create($body);
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id');
    }
}
