<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\WelcomeNotification;

class UserObserver
{
    public function creating(User $user)
    {
        if (empty($user->unique_id)) {
            $latest = User::orderByDesc('unique_id')->value('unique_id');
            $nextID = $latest ? intval(substr($latest, 3)) + 1 : 1;
            $user->unique_id = '000'.sprintf('%03d', $nextID);
        }
    }

    public function created(User $user)
    {
        $user->notify(new WelcomeNotification());
    }
}
