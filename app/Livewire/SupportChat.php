<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class SupportChat extends Component
{
    public $messageText = '';

    // Validation rules
    protected $rules = [
        'messageText' => 'required|string|max:500',
    ];

    public function sendMessage()
    {
        $this->validate();

        // Create Message
        Message::create([
            'user_id' => Auth::id(),
            'message' => $this->messageText,
            'is_admin_reply' => false,
            'is_read' => false,
        ]);

        // Reset input and dispatch event to scroll down
        $this->reset('messageText');
        $this->dispatch('message-sent');
    }

    public function deleteMessage($id)
    {
        $msg = Message::where('user_id', Auth::id())->find($id);
        if ($msg) {
            $msg->delete();
        }
    }

    public function render()
    {
        // Get messages
        $messages = Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.support-chat', compact('messages'));
    }
}
