<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseSuccessResource extends JsonResource
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var mixed
     */
    public $data;


    /**
     * args convetion as below
     * "key1"=>data1,"key2"=>data2,
     * e.g. 'stages'=>StageResource,"feedback"=>FeedbackResource,...so on
     * @param string $message
     * @param array  $data
     * @return array
     */
    public function __construct($data, $message="Operation Successfull")
    {
        $this->message = $message;
        $this->data    = $data;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }


}
