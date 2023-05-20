<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Name') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Event Details -->
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $event->name }}</h1>
                    <p class="text-gray-700 text-sm mb-4">Created by {{ $event->user_id }} on {{ $event->created_at->format('F jS, Y') }}</p>
                    <p class="text-gray-700 text-sm mb-4">Visibility: {{ ucfirst($event->visibility) }}</p>
                    <p class="text-gray-700 text-sm mb-4">Type: {{ ucfirst($event->type) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>