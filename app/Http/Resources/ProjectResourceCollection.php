<?php

namespace App\Http\Resources;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $collects = ProjectResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
