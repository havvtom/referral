<?php

namespace App\Http\Resources;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $collects = TaskResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
