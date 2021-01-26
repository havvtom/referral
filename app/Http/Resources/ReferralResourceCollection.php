<?php

namespace App\Http\Resources;

use App\Http\Resources\ReferralResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReferralResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $collects = ReferralResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
