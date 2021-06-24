<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionnaireSubmissionResource extends JsonResource
{
    /**
    @OA\Schema(
        title="QuestionnaireSubmission",
        schema="QuestionnaireSubmission",
        description="Schema of QuestionnaireSubmission response",
        @OA\Property(
            property = "key",
            description = "QuestionnaireSubmission key to access it",
            type = "string",
            example = "UzMOt5LIEvzkfFSh",
        ),
        @OA\Property(
            property = "questionnaire_id",
            description = "questionnaire id foreign key",
            type = "integer",
            example = 12,
        ),
        @OA\Property(
            property = "candidacy_id",
            description = "candidacy id foreign key",
            type = "integer",
            example = 134,
        ),
        @OA\Property(
            property = "metadata",
            description = "metadata having questions and answers",
            type = "json",
            example = {
                {
                    "answer": "I am a computer engineer who likes to explore new innovations and technologies in IT world.",
                    "question": "Tell us something about yourself."
                },
                {
                    "answer": "I was lost in forest in a tour at my school times. I shouted and my friends found me.",
                    "question": "What is the biggest problem have you faced in your life?  and how did you overcome it?"
                },
                {
                    "answer": "I can learn fast.",
                    "question": "What are your strengths?"
                }
            },
        ),
    ),

     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->only('key','questionnaire_id','candidacy_id','metadata');
    }
}
