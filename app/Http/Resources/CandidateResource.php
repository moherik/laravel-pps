<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CandidateResource extends JsonResource
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
            'name' => $this->name,
            'image' =>  $this->image != null ? Storage::url($this->image) : null,
            'order' => $this->order,
            'total_vote' => $this->votes->sum('total'),
            'percentage' => $this->percentage()
        ];
    }
}
