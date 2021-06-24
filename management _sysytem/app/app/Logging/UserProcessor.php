<?php

namespace App\Logging;

use App\User;
use Illuminate\Support\Facades\Auth;

class UserProcessor
{
    /**
     * @var mixed
     */
    private $user;


    /**
     * Create a new message instance.
     *
     * @param array $user
     */
    public function __construct($user = null)
    {
        if ($user) {
            $this->user = $user;
        }
    }


    public function __invoke(array $record)
    {
        // If we dont have a user, try getting it from Auth
        if ($this->user === null) {
            $this->user = Auth::user();
        }

        if ($this->user && get_class($this->user) === User::class) {
            $record['extra']['user_id'] = $this->user->key;
        }

        return $record;
    }


    /**
     * @param \App\User $user
     *
     * @return string
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
