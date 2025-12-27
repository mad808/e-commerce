<div class="container-fluid p-0 h-100 d-flex flex-column" style="max-height: calc(100vh - 100px);">

    <div class="card border-0 shadow-lg rounded-4 flex-grow-1 overflow-hidden d-flex flex-column">

        <!-- Chat Header -->
        <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between sticky-top" style="z-index: 10;">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.messages.index') }}" class="btn btn-light btn-sm rounded-circle me-3 shadow-sm" style="width: 40px; height: 40px; display: grid; place-items: center;">
                    <i class="bi bi-arrow-left fs-5 text-dark"></i>
                </a>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
                    <small class="text-muted">
                        {{ $user->email }} 
                        <!-- Live Indicator -->
                        <span wire:loading wire:target="sendMessage" class="text-primary ms-2 small">
                            <i class="bi bi-send"></i> sending...
                        </span>
                    </small>
                </div>
            </div>
            <div class="text-muted">
                <i class="bi bi-info-circle fs-5"></i>
            </div>
        </div>

        <!-- Chat Body (Messages) -->
        <!-- wire:poll.5s keeps the chat updated every 5 seconds -->
        <div class="card-body overflow-auto p-4 d-flex flex-column gap-3" 
             id="chatBox"
             wire:poll.5s
             style="background-color: #e5ddd5; background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">

            @foreach($messages as $msg)
            <div class="d-flex w-100 {{ $msg->is_admin_reply ? 'justify-content-end' : 'justify-content-start' }} message-group">

                <!-- Delete Button (Admin Side) -->
                @if($msg->is_admin_reply)
                <div class="align-self-center me-2 opacity-0 delete-action transition-fast">
                    <button wire:click="deleteMessage({{ $msg->id }})" 
                            wire:confirm="Delete this message?"
                            class="btn btn-link text-danger p-0">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                @endif

                <!-- Message Bubble -->
                <div class="position-relative p-3 shadow-sm animate__animated animate__fadeIn"
                    style="max-width: 75%; border-radius: 15px; 
                            {{ $msg->is_admin_reply 
                               ? 'background: #405FA5; color: white; border-bottom-right-radius: 2px;' 
                               : 'background: white; color: black; border-bottom-left-radius: 2px;' }}">

                    <p class="mb-1" style="font-size: 0.95rem; line-height: 1.4;">{{ $msg->message }}</p>

                    <div class="d-flex justify-content-end align-items-center gap-1">
                        <small style="font-size: 0.65rem; opacity: 0.7;">
                            {{ $msg->created_at->format('h:i A') }}
                        </small>
                        @if($msg->is_admin_reply)
                        <i class="bi bi-check2-all" style="font-size: 0.8rem; opacity: 0.7;"></i>
                        @endif
                    </div>
                </div>

                <!-- Delete Button (User Side Messages - Admin can delete these too) -->
                @if(!$msg->is_admin_reply)
                <div class="align-self-center ms-2 opacity-0 delete-action transition-fast">
                    <button wire:click="deleteMessage({{ $msg->id }})" 
                            wire:confirm="Delete User's message?"
                            class="btn btn-link text-danger p-0">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                @endif

            </div>
            @endforeach

        </div>

        <!-- Footer (Input) -->
        <div class="card-footer bg-white p-3 border-top">
            <form wire:submit="sendMessage" class="d-flex gap-2 align-items-center">
                
                <input type="text" 
                       wire:model="messageText"
                       class="form-control rounded-pill bg-light border-0 py-2 px-4"
                       placeholder="Type a message..." 
                       autocomplete="off" 
                       autofocus>

                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; background-color: #405FA5; border-color: #405FA5;">
                    <i class="bi bi-send-fill" wire:loading.remove></i>
                    <div wire:loading class="spinner-border spinner-border-sm text-white" role="status"></div>
                </button>
            </form>
            @error('messageText') <span class="text-danger small ms-3">{{ $message }}</span> @enderror
        </div>

    </div>

    <!-- Styles -->
    <style>
        #chatBox::-webkit-scrollbar { width: 6px; }
        #chatBox::-webkit-scrollbar-thumb { background-color: rgba(0, 0, 0, 0.2); border-radius: 10px; }
        .message-group:hover .delete-action { opacity: 1 !important; }
        .transition-fast { transition: opacity 0.2s ease-in-out; }
    </style>

    <script>
        function scrollToBottom() {
            const chatBody = document.getElementById('chatBox');
            if(chatBody) chatBody.scrollTop = chatBody.scrollHeight;
        }

        document.addEventListener("DOMContentLoaded", scrollToBottom);

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('message-sent', () => {
                setTimeout(scrollToBottom, 50);
            });
        });
    </script>
</div>