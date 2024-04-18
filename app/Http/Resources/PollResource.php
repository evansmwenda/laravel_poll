<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image,
            'image_url' => $this->image ? URL::to($this->image) : null,
            'status' => $this->status !== 'draft',
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expiry_date' => $this->expiry_date,
            'questions' => PollQuestionResource::collection($this->questions)
        ];
    }
}
