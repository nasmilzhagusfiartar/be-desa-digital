<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'thumbnail' => $this->thumbnail,
            'name' => $this->name,
            'about' => $this->about,
            'headman' => $this->headman,
            'people' => $this->people,
            'agriculture_area' =>(float)(string) $this->agriculture_area,
            'total_area' =>(float)(string) $this->total_area,
            'profile_images' => ProfileImageResource::collection($this->profileImages),
        ];
    }
}
