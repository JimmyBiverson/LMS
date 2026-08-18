@extends('layouts.app')

@section('title', 'Pending Approval')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m9-11a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pending Approval</h1>
        <p class="text-gray-600 mb-6">
            Your instructor account is pending approval. You'll be notified once an admin approves your account. You can now create courses and manage students at that point.
        </p>
        <a href="{{ route('logout') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>
@endsection
