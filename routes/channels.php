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
    $isAuthorized = (int) $user->id === (int) $id;
    
    Log::info('User channel access attempt', [
        'channel' => 'App.Models.User',
        'user_id' => $user->id,
        'requested_id' => $id,
        'is_authorized' => $isAuthorized,
        'user_email' => $user->email,
        'timestamp' => now()->toDateTimeString(),
        'ip_address' => request()->ip()
    ]);
    
    return $isAuthorized;
});

// Custom channel with logging
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    $isAuthorized = true; // Add your room access logic here
    
    Log::info('Chat room access attempt', [
        'channel' => 'chat',
        'user_id' => $user->id,
        'user_email' => $user->email,
        'room_id' => $roomId,
        'is_authorized' => $isAuthorized,
        'timestamp' => now()->toDateTimeString(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    return $isAuthorized;
});

// Notification channel with logging
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    $isAuthorized = (int) $user->id === (int) $userId;
    
    Log::info('Notification channel access attempt', [
        'channel' => 'notifications',
        'user_id' => $user->id,
        'user_email' => $user->email,
        'target_user_id' => $userId,
        'is_authorized' => $isAuthorized,
        'timestamp' => now()->toDateTimeString(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    return $isAuthorized;
});

// System channel for admin notifications
Broadcast::channel('system.admin', function ($user) {
    $isAuthorized = $user->is_admin ?? false;
    
    Log::info('System admin channel access attempt', [
        'channel' => 'system.admin',
        'user_id' => $user->id,
        'user_email' => $user->email,
        'is_authorized' => $isAuthorized,
        'timestamp' => now()->toDateTimeString(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    return $isAuthorized;
});
