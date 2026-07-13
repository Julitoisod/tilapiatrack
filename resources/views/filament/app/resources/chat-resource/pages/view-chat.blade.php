<x-filament-panels::page>
    <div class="p-4 space-y-4" x-data="{ messages: @entangle('messages') }" x-init="
        Echo.private('chat.' + @js($this->getRecord()->id))
            .listen('NewMessage', (e) => {
                messages.push(e.message);
            });
    ">
        <div class="h-96 overflow-y-auto border rounded-lg p-4 space-y-4">
            <!-- Display existing messages -->
            @foreach($this->getRecord()->messages()->with('user')->orderBy('created_at', 'asc')->get() as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="{{ $message->user_id === auth()->id() ? 'bg-primary-500' : 'bg-gray-100' }} rounded-lg p-3 max-w-md"
                        style="color: {{ $message->user_id === auth()->id() ? '#ffffff' : '#111827' }}">
                        <p class="text-sm font-semibold">{{ $message->user->name }}</p>
                        <p>{{ $message->message }}</p>
                        <p class="text-xs opacity-75">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <form wire:submit.prevent="sendMessage">
            <div class="flex space-x-2">
                <x-filament::input.wrapper class="flex-1">
                    <x-filament::input
                        type="text"
                        wire:model="messageContent"
                        placeholder="Type your message..."
                    />
                </x-filament::input.wrapper>
                <x-filament::button type="submit">
                    Send
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
