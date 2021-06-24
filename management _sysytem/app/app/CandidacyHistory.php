<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CandidacyHistory extends Model
{
    /**
    @OA\Schema(
        title="CandidacyHistory",
        schema="CandidacyHistory",
        description="Schema of CandidacyHistory model",
        @OA\Property(
            property = "id",
            description = "history id to access it",
            type = "integer",
            example = 12,
        ),
        @OA\Property(
            property = "key",
            description = "history key to access it",
            type = "string",
            example = "UzMOt5LIEvzkfFSh",
        ),
        @OA\Property(
            property = "candidacy_id",
            description = "candidacy id for foreign key",
            type = "integer",
            example = 21,
        ),
        @OA\Property(
            property = "stage_name",
            description = "stage name",
            type = "string",
            example = "Pair Programming",
        ),
        @OA\Property(
            property = "status",
            description = "status of stage",
            type = "string",
            example = "created",
        ),
        @OA\Property(
            property = "actor",
            description = "actor/user name",
            type = "string",
            example = 11,
        ),
        @OA\Property(
            property = "metadata",
            description = "metadata related to history entry",
            type = "object",
            example = { "key1":"data1", "key2":"data2", "key3":"data3"},
        ),
        @OA\Property(
            property = "created_at",
            description = "Date Time of creation of record",
            type = "string",
            format = "date-time",
            example = "2021-04-05T11:46:58+00:00",
        ),
    ),
    */

    const UPDATED_AT = null;
    /**
     * @var \Datetime
     */
    public $timestamps = ['created_at'];


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
        'candidacy_id',
        'stage_name',
        'status',
        'actor',
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



    /**
     * creates CandidacyHistory from predefined array and request params
     * 1.default can be used for parameters which are required and can be mapped directly to history table
     *
     *
     * @param array $default  -> default array that can be directly mapped and assigned to CandidacyHistory
     * @param Illuminate\Http\Request $request -> request object recieved to controller
     * @param array $params -> array of parameter names which are to be set from request. e.g. $params[0]="metadata",  $history->metadata = $request->$params[0] // purpose : for setting non required fields.
     * @param array $extra ->extra data for param, e.g.["metadata"=>["actor_key"=>]]
     * @return CandidacyHistory
    */
    public static function createFromData(array $default, Request $request = null, array $params = null, array $extra = null)
    {
        $history = new CandidacyHistory();
        //default values
        foreach ($default as $key => $value) {
            $history->$key = $value;
        }

        //request values as per params
        foreach ($params as $param) {
            if (isset($request->$param) === true) {
                $history->$param = $request->$param + (isset($extra[$param]) ? $extra[$param] : []);
            } else {
                $history->$param = (isset($extra[$param]) ? $extra[$param] : []);
            }
        }

        $history->save();

        return $history;
    }
}
