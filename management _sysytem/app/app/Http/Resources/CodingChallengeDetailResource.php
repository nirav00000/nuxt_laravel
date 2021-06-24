<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CodingChallengeDetailResource extends JsonResource
{
     /**
    @OA\Schema(
    title="CodingChallengeDetailed",
    schema="CodingChallengeDetailed",
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
        ),
        @OA\Property(
            property = "tests",
            description = "coding challenge testcases",
            example = "[{inputs: ""5, 3"", output: ""8""}, {inputs: ""0, 2"", output: ""2""}]"
        ), 
    )
    */
    public function toArray($request)
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'description' => $this->description,
            'tests' => $this->tests
        ];
    }
}
