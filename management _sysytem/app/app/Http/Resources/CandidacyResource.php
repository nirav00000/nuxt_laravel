<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use stdClass;

class CandidacyResource extends JsonResource
{
   /**
    @OA\Schema(
    title="Candidacy",
    schema="Candidacy",
    description="Schema of candidacy response",
        @OA\Property(
            property = "key",
            description = "candidacy key to access it",
            type = "string",
            example = "UzMOt5LIEvzkfFSh",
        ),
        @OA\Property(
            property = "candidate",
            description = "Details of candidate",
            type="object",
            ref="#/components/schemas/Candidate",
        ),
        @OA\Property(
            property = "position",
            description = "Position for which candidate applied",
            type = "string",
            example = "Software Developer",
        ),
        @OA\Property(
            property = "final_status",
            description = "Final status of candidacy",
            type = "string",
            example = "inactive",
        ),
        @OA\Property(
            property = "metadata",
            description = "Metadata related to candidacy",
            @OA\Property(
                property = "stages",
                description = "Metadata related to status of stages",
                type = "array",
                @OA\Items(),
                example = "{ stageName1:{latest data here}, stageName2:{latest data here} }",
            ),
            @OA\Property(
                property = "feedbacks",
                description = "Metadata related to feedbacks",
                type = "array",
                @OA\Items(),
                example = "{ feedback_id: 1, verdict: May be, Actor: actor3 },{ feedback_id: 2, verdict: Yes, Actor: actor2 },{ feedback_id: 3, verdict: Yes, Actor: actor3 }",
            ),
        ),
        @OA\Property(
            property = "created_at",
            description = "Date of creation of candidacy",
            type = "string",
            format="date",
            example = "2021-03-05T08:57:11+00:00",
        ),
    ),
    @OA\Schema(
        title="Pagination Meta",
        schema="paginationMeta",
        description="Schema of Meta array of pagination ",
        @OA\Property(
            property = "current_page",
            description = "current page number",
            type = "integer",
            example = 1,
        ),
        @OA\Property(
            property = "first_page_url",
            description = "url of first page",
            type = "string",
            example = "http://localhost/api/v1/candidacies?&page=1",
        ),
        @OA\Property(
            property = "from",
            description = "first record in current page",
            type = "integer",
            example = 1,
        ),
        @OA\Property(
            property = "last_page",
            description = "last page number",
            type = "integer",
            example = 9,
        ),
        @OA\Property(
            property = "last_page_url",
            description = "last page url",
            type = "string",
            example = "http://localhost/api/v1/candidacies?&page=9",
        ),
        @OA\Property(
            property = "next_page_url",
            description = "url of next page",
            type = "string",
            example = "http://localhost/api/v1/candidacies?&page=2",
        ),
        @OA\Property(
            property = "path",
            description = "path of query",
            type = "string",
            example ="http://localhost/api/v1/candidacies?",
        ),
        @OA\Property(
            property = "per_page",
            description = "number of records per page",
            type = "integer",
            example =15,
        ),
        @OA\Property(
            property = "prev_page_url",
            description = "url of previous page",
            type = "string",
            example ="http://localhost/api/v1/candidacies?&page=9",
        ),
        @OA\Property(
            property = "to",
            description = "last record in the page",
            type = "integer",
            example =15,
        ),
        @OA\Property(
            property = "total",
            description = "total records of all pages",
            type = "integer",
            example =152,
        ),
    ),
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        return [
            'key'=>$this->key,
            'candidate'=>new CandidateResource($this->load('candidate')->candidate),
            'position'=>$this->position,
            'final_status'=>$this->final_status,
            'metadata'=>$this->getMetadata($request),
            'created_at'=>$this->created_at,
        ];
    }

    public function getMetadata($request)
    {
        if(in_array(config("ldap.admin"), $request->oauth_groups)) {
            return $this->metadata;
        }
        else {
            // If empty metadata
            if(empty($this->metadata))
            return $this->metadata;

            $actor = Auth::user();

            // New metadata
            $newMetadata = [];
           
            if(isset($this->metadata["stages"])) {
                // Fetch related stages in which candidacies assigned
                foreach($this->metadata["stages"] as $key => $val) {
                    if((isset($this->metadata["stages"][$key]["assignee_key"]) && $this->metadata["stages"][$key]["assignee_key"] === $actor->key)) {
                        $newMetadata["stages"][$key] = $this->metadata["stages"][$key];
                    }
                }
            }
            if(isset($this->metadata["closing"])) {
                $newMetadata['closing'] = $this->metadata["closing"];
            }

            return $newMetadata;
        }
    }
}