<?php

namespace App\Services;

use App\User;
use Log;
use Illuminate\Support\Arr;

class UserService
{


    public function create(array $userData)
    {

        debug(
            'UserService->create() - Creating new user...',
            [
                'data'    => $userData,
            ]
        );

        $userData['password'] = $userData['api_key'];

        $user = User::create(
            Arr::only(
                $userData,
                [
                    'name',
                    'email',
                    'password',
                ]
            )
        );

        info(
            'UserService->create() - Created new User',
            [
                'user_id' => $user->id,
            ]
        );

        return true;
    }


    public function update(User $user, $userData)
    {

        debug(
            'UserService->update() - Updating user...',
            [
                'user' => $user,
                'data' => $userData,
            ]
        );

        $userData['password'] = $userData['api_key'];

        $user->update(
            Arr::only(
                $userData,
                [
                    'name',
                    'password',
                ]
            )
        );

        info(
            'UserService->update() - Updated User',
            [
                'user_id' => $user->id,
            ]
        );

        return true;
    }


    public function delete(User $user)
    {

        debug(
            'UserService->delete() - Deleting user...',
            [
                'user'    => $user,
            ]
        );

        $user->delete();

        info(
            'UserService->destroy() - Deleted User',
            [
                'user_id' => $user->id,
            ]
        );

        return true;
    }


    public function restore(User $user)
    {

        debug(
            'UserService->restore() - Restoring user...',
            [
                'user'    => $user,
            ]
        );

        $user->restore();

        info(
            'UserService->restore() - Restored User',
            [
                'user_id' => $user->id,
            ]
        );

        return true;
    }
}
