<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CodingChallengeResource extends JsonResource
{
   /**
    @OA\Schema(
    title="CodingChallengeSchema",
    schema="CodingChallengeSchema",
    description="Schema of coding challenge response",
        @OA\Property(
            property = "key",
            description = "coding challenge id",
            type = "string",
            example = "JgQ4K9qw8usvj4i9",
        ),
        @OA\Property(
            property = "title",
            description = "coding challenge title",
            type = "string",
            example = "Two number sum"
        ),
        @OA\Property(
            property = "description",
            description = "coding challenge description",
            type = "string",
            example = "Write a program that example..",
        )    
    )
    */
    public function toArray($request)
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'description' =>$this->description
        ];
    }
}
