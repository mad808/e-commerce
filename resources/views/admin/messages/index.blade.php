@extends('admin.layouts.app')
@section('title', 'Messages')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="min-height: 600px;">

            <!-- Header -->
            <div class="card-header bg-white p-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-dark">Inbox</h4>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $users->count() }} Conversations</span>
                </div>
                <!-- Optional Search Bar logic could go here -->
            </div>

            <!-- List -->
            <div class="list-group list-group-flush custom-scrollbar" style="overflow-y: auto; height: 600px;">
                @forelse($users as $user)
                <a href="{{ route('admin.messages.show', $user->id) }}"
                    class="list-group-item list-group-item-action p-3 border-bottom-0 border-top position-relative transition-hover {{ $user->unread_count > 0 ? 'bg-blue-light' : '' }}">

                    <div class="d-flex align-items-center">
                        <!-- Avatar -->
                        <div class="position-relative me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                style="width: 55px; height: 55px; background: linear-gradient(135deg, #405FA5, #60a5fa); font-size: 1.2rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            @if($user->unread_count > 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle"></span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $user->name }}</h6>
                                <small class="text-muted ms-2" style="font-size: 0.75rem;">
                                    {{ $user->last_message->created_at->diffForHumans(null, true, true) }}
                                </small>
                            </div>

                            <p class="mb-0 text-truncate {{ $user->unread_count > 0 ? 'fw-bold text-dark' : 'text-muted' }}" style="max-width: 85%;">
                                @if($user->last_message->is_admin_reply)
                                <span class="text-primary me-1"><i class="bi bi-reply-fill"></i> You:</span>
                                @endif
                                {{ $user->last_message->message }}
                            </p>
                        </div>

                        <!-- New Badge -->
                        @if($user->unread_count > 0)
                        <div class="ms-3">
                            <span class="badge bg-danger rounded-pill shadow-sm">{{ $user->unread_count }}</span>
                        </div>
                        @endif
                    </div>
                </a>
                @empty
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted mt-5">
                    <i class="bi bi-chat-square-dots display-1 opacity-25 mb-3"></i>
                    <p>No messages yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .bg-blue-light {
        background-color: #f8faff;
    }

    .transition-hover:hover {
        background-color: #f1f5f9;
        border-left: 4px solid #405FA5;
    }

    .list-group-item {
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }
</style>
@endsection