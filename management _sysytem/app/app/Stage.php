<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Stage extends Model
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
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'type',
        'metadata',
    ];


    protected $casts = [
        "metadata" => "json"
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
