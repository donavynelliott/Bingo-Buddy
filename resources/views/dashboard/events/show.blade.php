<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Name') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Success/Error -->
                @if (session('success'))
                    <div class="bg-green-500 p-4 rounded-lg mb-6 text-white text-center">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Event Details -->
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $event->name }}</h1>
                    <p class="text-gray-700 text-sm mb-4">Created by {{ $event->user_id }} on {{ $event->created_at->format('F jS, Y') }}</p>
                    <p class="text-gray-700 text-sm mb-4">Visibility: {{ ucfirst($event->visibility) }}</p>
                    <p class="text-gray-700 text-sm mb-4">Type: {{ ucfirst($event->type) }}</p>
                </div>
                <!-- Join/Leave Event button -->
                <div class="p-6 text-gray-900">
                    <!-- Leave Button -->
                    @if ($event->users->contains(auth()->id()))
                        <form action="{{ route('events.leave', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-3 rounded font-medium w-full">Leave Event</button>
                        </form>
                    @else
                    <!-- Join Button -->
                        <form action="{{ route('events.join', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-teal-500 text-white px-4 py-3 rounded font-medium w-full">Join Event</button>
                        </form>
                    @endif
                </div>
                    
            </div>
        </div>
    </div>
</x-app-layout>