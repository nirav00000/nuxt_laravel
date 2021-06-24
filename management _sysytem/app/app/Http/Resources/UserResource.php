<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
        @OA\Schema(
            title="User Schema",
            schema="User",
            description="Schema of User Response",
            @OA\Property(
                property = "key",
                description = "User key to access it",
                type = "string",
                example = "RLvAbH",
            ),
            @OA\Property(
                property = "name",
                description = "User name",
                type = "string",
                example = "Allen Walsh",
            ),
            @OA\Property(
                property = "email",
                description = "User email",
                type = "string",
                example = "werdman@example.org",
            ),
        ),  
    */




    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "key"=>$this->key,
            "name"=>$this->name,
            "email"=>$this->email
        ];
    }
}
