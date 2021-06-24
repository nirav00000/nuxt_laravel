<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Validation\ValidationException;

class Candidate extends Model
{
    use SoftDeletes;
    use LogsActivity;


     /**
      * Get the route key for the model.
      *
      * @return string
      */
    public function getRouteKeyName()
    {
        return 'key';
    }


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // set the key to a temp value. It will be replaced with the hash of the id by the model observer
        $this->attributes['key'] = Str::random();
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'email',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'json',
    ];

     /**
      * LogsActivity - Only log fillable attributes
      * @var boolean
      */
    protected static $logFillable = true;
    /**
     * LogsActivity - Only log dirty attributes
     * @var boolean
     */
    protected static $logOnlyDirty = true;



    // for referance
    // public function candidacy()
    // {
    // return $this->hasMany('App\Candidacy','candidate_id','id');
    // }


    /**
     * updates metadata of candidate, only which are provided
     * @param Request $request
     */
    public function applyRequest(Request $request)
    {

        $this->name    = $request->name;
        $this->email   = $request->email;
        $arrayMetadata = $this->metadata;
        if (isset($request->metadata["experience"]) && ($request->metadata["experience"] < 0)) {
            throw ValidationException::withMessages(["experience" => "Experience field can not be negative"]);
        }
        if ($request->metadata) {
            foreach ($request->metadata as $key => $metaValue) {
                $arrayMetadata[$key] = $metaValue;
            }
        }

        $this->metadata = $arrayMetadata;
        //resumes in  metadata of candidacy
        if ($request->candidacy_key && $request->candidacy_resume) {
            $candidacy = Candidacy::where('key', $request->candidacy_key)->first();
            $metadata = $candidacy->metadata ?? [];

            $metadata["resumes"] = $metadata["resumes"] ?? []; //first resume
            $new_resume = ["link" => $request->candidacy_resume,"added_at" => now()];
            array_push($metadata["resumes"], $new_resume);

            $candidacy->metadata = $metadata;
            $candidacy->save();
        }
        $this->save();
    }
}
