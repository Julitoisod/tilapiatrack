<?php

namespace App\Filament\App\Resources\ChatResource\Pages;

use App\Events\NewMessage;
use App\Filament\App\Resources\ChatResource;
use App\Models\Message;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;

class ViewChat extends ViewRecord
{
    protected static string $resource = ChatResource::class;

    protected static string $view = 'filament.app.resources.chat-resource.pages.view-chat';

    public $messageContent = '';
    public $messages = [];

    protected $listeners = ['refreshMessages' => '$refresh'];

    public function mount($record): void
    {
        parent::mount($record);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->record->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'messageContent' => 'required|string',
        ]);

        // Create the message
        $message = Message::create([
            'chat_id' => $this->record->id,
            'user_id' => Auth::id(),
            'message' => $this->messageContent,
        ]);

        // Clear input
        $this->messageContent = '';

        // Load updated messages
        $this->loadMessages();

        // Dispatch the event for real-time broadcasting
      //  broadcast(new NewMessage($message));

        // Refresh the messages
        $this->dispatch('refreshMessages');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
