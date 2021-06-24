<?php

namespace App\Observers;

use App\User;
use App\Events\User\UserWasCreated;
use Vinkla\Hashids\Facades\Hashids;

class UserObserver
{


    /**
     * Listen to the User created event.
     *
     * @param  \App\User $user
     *
     * @return void
     */
    public function created(User $user)
    {
        // debug('User created');
        // event(new UserWasCreated($user));
        $encoded_id = Hashids::encode($user->id);
        $user->key = $encoded_id;
        $user->save();
    }


     /**
      * Listen to the User updated event.
      *
      * @param  \App\User $user
      *
      * @return void
      */
    public function updated(User $user)
    {
        debug('User updated');
    }


     /**
      * Listen to the User updated event.
      *
      * @param  \App\User $user
      *
      * @return void
      */
    public function deleted(User $user)
    {
        debug('User deleted');
    }
}
