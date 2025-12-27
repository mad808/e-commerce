<div class="container py-4" style="height: calc(100vh - 100px);">
    <div class="row justify-content-center h-100">
        <div class="col-md-8 h-100">

            <!-- Chat Card -->
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden d-flex flex-column">

                <!-- 1. Header -->
                <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between sticky-top" style="z-index: 10;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-headset fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Customer Support</h5>
                            <small class="text-success d-flex align-items-center gap-1">
                                <span class="position-relative d-flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-100 w-100 rounded-circle bg-success opacity-75"></span>
                                  <span class="relative inline-flex rounded-circle h-2 w-2 bg-success"></span>
                                </span>
                                Online
                            </small>
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Close Chat">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

                <!-- 2. Chat Body -->
                <!-- wire:poll.5s keeps checking for new messages every 5 seconds -->
                <div class="card-body overflow-auto p-4 d-flex flex-column gap-3 flex-grow-1" 
                     id="chatBox"
                     wire:poll.5s
                     style="background-color: #e5ddd5; background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">

                    @forelse($messages as $msg)
                        <div class="d-flex w-100 {{ $msg->is_admin_reply ? 'justify-content-start' : 'justify-content-end' }} message-group">

                            <!-- Delete Button (User Side) -->
                            @if(!$msg->is_admin_reply)
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
                                        ? 'background: white; color: black; border-bottom-left-radius: 2px;' 
                                        : 'background: #405FA5; color: white; border-bottom-right-radius: 2px;' }}">

                                @if($msg->is_admin_reply)
                                    <div class="fw-bold text-primary mb-1" style="font-size: 0.75rem;">Support Agent</div>
                                @endif

                                <p class="mb-1" style="font-size: 0.95rem; line-height: 1.4;">{{ $msg->message }}</p>

                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    <small style="font-size: 0.65rem; opacity: 0.7;">
                                        {{ $msg->created_at->format('h:i A') }}
                                    </small>
                                    @if(!$msg->is_admin_reply)
                                        <i class="bi bi-check2-all" style="font-size: 0.8rem; opacity: 0.7;"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center mt-5">
                            <div class="bg-white p-3 rounded-pill shadow-sm d-inline-block">
                                <p class="mb-0 text-muted small">👋 Hi! How can we help you today?</p>
                            </div>
                        </div>
                    @endforelse

                </div>

                <!-- 3. Footer (Input) -->
                <div class="card-footer bg-white p-3 border-top">
                    <form wire:submit="sendMessage" class="d-flex gap-2 align-items-center">
                        
                        <input type="text" 
                               wire:model="messageText" 
                               class="form-control rounded-pill bg-light border-0 py-2 px-4"
                               placeholder="Type a message..." 
                               autocomplete="off">

                        <button type="submit" 
                                class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 45px; height: 45px; background-color: #405FA5; border-color: #405FA5;"
                                wire:loading.attr="disabled">
                            
                            <!-- Icon shows when NOT loading -->
                            <i class="bi bi-send-fill fs-5" wire:loading.remove></i>
                            
                            <!-- Spinner shows WHEN loading -->
                            <div wire:loading class="spinner-border spinner-border-sm text-white" role="status"></div>
                        </button>
                    </form>
                    @error('messageText') <span class="text-danger small ms-3">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>
    </div>

    <!-- Styles specific to this component -->
    <style>
        #chatBox::-webkit-scrollbar { width: 6px; }
        #chatBox::-webkit-scrollbar-thumb { background-color: rgba(0, 0, 0, 0.2); border-radius: 10px; }
        .message-group:hover .delete-action { opacity: 1 !important; }
        .transition-fast { transition: opacity 0.2s ease-in-out; }
    </style>

    <!-- Auto Scroll Script using Livewire Events -->
    <script>
        function scrollToBottom() {
            const chatBody = document.getElementById('chatBox');
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // Scroll on load
        document.addEventListener("DOMContentLoaded", scrollToBottom);

        // Scroll when message sends
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('message-sent', () => {
                setTimeout(scrollToBottom, 50); // Small delay to allow DOM render
            });
        });
    </script>
</div>