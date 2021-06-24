<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;
use stdClass;

class Candidacy extends Model
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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'metadata' => '{}',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'candidate_id',
        'position',
        'final_status',
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


    public function candidate()
    {
        return $this->belongsTo('App\Candidate', 'candidate_id', 'id');
    }


    public function histories()
    {
        return $this->hasMany(CandidacyHistory::class, 'candidacy_id', 'id');
    }

    /**
     * update candidact metadata of stage
     * @param array $data
    */
    public function updateStageMetadata()
    {
        $histories = $this->histories()->orderBy('created_at')->get();

        $stages = new stdClass();
        $assignees = new stdClass();
        $closing = new stdClass();
        $assignees_track = new stdClass();

        // Iterate history
        foreach ($histories as $history) {
            // All stages
            if ($history->stage_name) {
                $stage_name = $history->stage_name;
                $actor = $history->actor;
                $history_key = $history->key;
                $metadata = $history->metadata;
                // Track of created stages
                if ($history->status === 'created') {
                    $stages->$stage_name = [
                        'stage_name' => $stage_name,
                        'actor' => $actor,
                        'history_key' => $history_key,
                        'actory_key' => $metadata['actor_key'],
                        'assignee_key' => $metadata['assignee_key'],
                        'assignee' => $metadata['assignee_name']
                    ];

                    // Create list of assignees and add track for faster retrival
                    $assignee_key = $metadata['assignee_key'];
                    $assignees->$assignee_key[$stage_name] = 'created';
                    $assignees_track->$stage_name = $metadata['assignee_key'];
                } else if ($history->status === 'completed') {
                    // If completed remove stages, and remove from assignees
                    unset($stages->$stage_name);
                    $assinee_key = $assignees_track->$stage_name;
                    unset($assignees->$assinee_key[$stage_name]);
                }
            } else if (isset($history->metadata['candidacy_closing_reason'])) {
                // Track candidacy closing and add
                $actor = $history->actor;
                $actor_key = $history->metadata['actor_key'];
                $close_reason = $history->metadata['candidacy_closing_reason'];
                $history_key = $history->key;

                $closing->actor = $actor;
                $closing->actor_key = $actor_key;
                $closing->reason = $close_reason;
                $closing->history_key = $history_key;
            }
        }

        // Iterate assignee and remove if all assigned stages
        foreach ($assignees as $key => $val) {
            if (!$val) {
                unset($assignees->$key);
            }
        }

        $metadata = new stdClass();

        // Old keys remains as it is
        foreach ($this->metadata as $key => $value) {
            $metadata->$key = $value;
        }

        $metadata->assignees = $assignees;
        $metadata->stages = $stages;
        $metadata->closing = $closing;

        $this->metadata = $metadata;
        $this->save();
    }
}
