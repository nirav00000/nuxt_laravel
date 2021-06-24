<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class CandidateResource extends JsonResource
{
    /**
     * @OA\Schema(
     * title="Candidate",
     * schema="Candidate",
     * description="Response structure for Candidate",
     * @OA\Xml(name="Candidate"),
     
    
        @OA\Property(
            property = "key",
            description = "Key of Candidate",
            type = "string",
            example = "dsfJJJDSjSdjfs",
        ),
        @OA\Property(
            property = "name",
            description = "Name of Candidate",
            type = "string",
            example = "Hallie Pfeffer",
        ),
        @OA\Property(
            property = "email",
            description = "Email of candidate",
            type = "string",
            example = "student@gmail.com",
        ),
        @OA\Property(
            property = "metadata",
            description = "Metadata of candidate",
            @OA\Property(
                property = "contact",
                description = "Contact no of candidate",
                type = "string",
                example = "+1 345 321 2311",
            ),
            @OA\Property(
                property = "education",
                description = "Education of candidate",
                type = "string",
                example = "BE CE",
            ),
            @OA\Property(
                property = "college",
                description = "College of last education",
                type = "string",
                example = "IIT Kankot",
            ),
            @OA\Property(
                property = "experience",
                description = "College of last education",
                type = "integer",
                example = 12,
            ),
            @OA\Property(
                property = "last_company",
                description = "Last company",
                type = "string",
                example = "Google",
            ),
        ),
     )



    * @param  \Illuminate\Http\Request $request
    * @return array
    */
    public function toArray($request)
    {
	
        return [
            'key'          => $this->key,
            'name'         => $this->name,
            'email'        => $this->email,
            'metadata'     => $this->metadata,
        ];
    }



}
