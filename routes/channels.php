<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('User channel access attempt', [
        'user_id' => $user->id,
        'requested_id' => $id,
        'timestamp' => now()
    ]);
    return (int) $user->id === (int) $id;
});

// Custom channel with logging
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    Log::info('Chat room access attempt', [
        'user_id' => $user->id,
        'room_id' => $roomId,
        'timestamp' => now()
    ]);
    
    // Add your room access logic here
    return true; // or your custom logic
});

// Notification channel with logging
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    Log::info('Notification channel access attempt', [
        'user_id' => $user->id,
        'target_user_id' => $userId,
        'timestamp' => now()
    ]);
    
    return (int) $user->id === (int) $userId;
});
