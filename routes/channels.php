<?php

use App\Helpers\Cryptor;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('user-notification-channel.{id}', function ($user, $id) {
    return Cryptor::encrypt($user->id) === $id;
});

Broadcast::channel('admin-notification-channel', function ($user) {
    return $user->role == 1;
});
