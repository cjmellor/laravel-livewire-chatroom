<?php

namespace App\Broadcasting;

use App\Models\User;

class UserChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function join(User $user): array
    {
        return [
            'user_id' => $user->id,
        ];
    }
}
