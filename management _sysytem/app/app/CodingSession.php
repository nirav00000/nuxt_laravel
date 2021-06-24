<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;
use App\Candidacy;
use App\CodingChallenge;

class CodingSession extends Model
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
        'challenge_id',
        'candidacy_id',
        'code',
        'language',
        'started_at',
        'submitted_at'
    ];


    /**
     * A session has candidacy_id
     */
    public function candidacy()
    {
        return $this->belongsTo(Candidacy::class, 'candidacy_id', 'id');
    }


    public function codingChallenge()
    {
        return $this->belongsTo(CodingChallenge::class, 'challenge_id', 'id');
    }
}
