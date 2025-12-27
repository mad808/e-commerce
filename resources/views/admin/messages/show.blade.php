@extends('admin.layouts.app')
@section('title', 'Chat')

@section('content')

    @livewire('admin-chat', ['userId' => $userId])

@endsection