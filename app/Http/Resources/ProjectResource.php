<?php

namespace App\Http\Resources;

use App\Http\Resources\TaskResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => Str::limit($this->description, 60),
            'tasks' => new TaskResourceCollection($this->tasks)
        ];
    }
}
