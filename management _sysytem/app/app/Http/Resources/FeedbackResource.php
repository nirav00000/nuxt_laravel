<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{		

    /**
    @OA\Schema(
        title="Feedback",
        schema="Feedback",
        description="Feedback",
        @OA\Property(
            property = "key",
            description = "Feedback key to access it",
            type = "string",
            example = "UzMOt5",
        ),
        @OA\Property(
            property = "user_id",
            description = "User Id",
            type = "integer",
            example = "1",
        ),
        @OA\Property(
            property = "verdict",
            description = "Verdict",
            type = "string",
            example = "Yes",
        ),
        @OA\Property(
            property = "description",
            description = "Descriptive Feedback",
            type = "string",
            example = "Technically Sound",
        ),
    ),

     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [   
            'key'           => $this->key,
            'user_id'       => $this->user_id,
            'verdict'       => $this->verdict,
            'description'   => $this->description,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
