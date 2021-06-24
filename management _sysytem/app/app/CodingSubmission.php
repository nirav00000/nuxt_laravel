<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;
use App\CodingSession;

class CodingSubmission extends Model
{
    use SoftDeletes;

    use LogsActivity;


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


    protected $fillable = [
        'key',
        'session_id',
        'language',
        'code',
        'total_tests',
        'passed_tests',
        'result',
    ];


    /**
     * A Coding submission has session_id
     */
    public function codingSession()
    {
        return $this->belongsTo(CodingSession::class, 'session_id', 'id');
    }
}
