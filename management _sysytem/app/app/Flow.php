<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Flow extends Model
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
        $this->attributes['key'] = Str::random(6);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'key',
        'flow_name',
        'rounds',
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
}
