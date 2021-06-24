<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionnaireResource extends JsonResource
{
    /**
    @OA\Schema(
    title="Questionnaire",
    schema="Questionnaire",
    description="Schema of Questionnaire response",
        @OA\Property(
            property = "key",
            description = "questionnaire key to access it",
            type = "string",
            example = "UzMOt5LIEvzkfFSh",
        ),
        @OA\Property(
            property = "name",
            description = "Name of questionnaire",
            type = "string",
            example = "Software Developer Questionnaire",
        ),
        @OA\Property(
            property = "metadata",
            description = "Metadata related to questionnaire",
            example = {"url":"https://form1.com/d/s/formXYZ"},
        ),
    ),
    
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        return $this->only('key','name','metadata');
    }
}
