<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Only the two participants of a chat may subscribe to its private channel.
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return Chat::where('id', $chatId)
        ->where(fn ($q) => $q->where('admin_id', $user->id)->orWhere('beneficiary_id', $user->id))
        ->exists();
});
