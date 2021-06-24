<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseFailureResource extends JsonResource
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var mixed
     */
    public $error;


    /**
     * args convetion as below
     * "key1"=>errorData1,"key2"=>errorData2,
     * e.g. 'errorMessage'=>'SQLState ERROR[XYZ00]',"stackTrace"=>fullData,...so on
     * @param string    $message
     * @param Exception $error
     * @return array
     */
    public function __construct($error, $message="Operation Failed")
    {
        $this->message = $message;
		
		if(empty($error))
		{ 
			$this->error = null;
		}
		else
		{ 
        	$this->error   = ["Exception Class" => get_class($error),"Exception Message" => $error->getMessage(),"Trace" => $error->getTrace()];
		}
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
            'success' => false,
            'message' => $this->message,
            'error'   => $this->error,
        ];
    }


}
