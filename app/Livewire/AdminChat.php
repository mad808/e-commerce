<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class AdminChat extends Component
{
    public $userId; // The client we are talking to
    public $messageText = '';

    protected $rules = [
        'messageText' => 'required|string|max:1000',
    ];

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function sendMessage()
    {
        $this->validate();

        // --- SECURITY: Rate Limiting (DDoS Protection) ---
        // Allow max 10 messages per minute for this admin session
        $key = 'admin-chat:' . auth()->id();
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('messageText', "Too fast! Please wait $seconds seconds.");
            return;
        }
        RateLimiter::hit($key);
        // ------------------------------------------------

        // Create Reply
        Message::create([
            'user_id' => $this->userId,
            'message' => $this->messageText,
            'is_admin_reply' => true,
            'is_read' => false,
        ]);

        $this->reset('messageText');
        $this->dispatch('message-sent');
    }

    public function deleteMessage($id)
    {
        $msg = Message::find($id);
        if ($msg && $msg->user_id == $this->userId) {
            $msg->delete();
        }
    }

    public function render()
    {
        $user = User::findOrFail($this->userId);
        
        Message::where('user_id', $this->userId)
            ->where('is_admin_reply', false)
            ->update(['is_read' => true]);

        $messages = Message::where('user_id', $this->userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.admin-chat', [
            'messages' => $messages,
            'user' => $user
        ]);
    }
}