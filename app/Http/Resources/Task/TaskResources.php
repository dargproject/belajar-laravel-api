<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResources extends JsonResource
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
            'user_name' => $this->user->name,
            'title' => $this->title,
            'description' => $this->description,
            'file' => $this->upload_file,
            'updated_at' => $this->updated_at->translatedformat('d F Y'),
            'created_at' => $this->created_at->translatedformat('d F Y'),
        ];
    }
}
