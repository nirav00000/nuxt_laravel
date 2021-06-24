<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{


    /**
    @OA\Schema(
        title="Stage",
        schema="Stage",
        description="Schema of Stage response",
        @OA\Property(
            property = "key",
            description = "stage key to access it",
            type = "string",
            example = "UzMOt5LIEvzkfFSh",
        ),
        @OA\Property(
            property = "name",
            description = "Name of stage",
            type = "string",
            example = "HR Interview",
        ),
        @OA\Property(
            property = "type",
            description = "Type of stage",
            type = "string",
            example = "interview",
        ),
        @OA\Property(
            property = "metadata",
            description = "Metadata related to stage",
            example={"key1":"value1","key2":"value2"},
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
            'key'      => $this->key,
            'name'     => $this->name,
            'type'     => $this->type,
            'metadata' => $this->metadata,
        ];
    }


}
