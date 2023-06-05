<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($event->name . " Team Setup") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h1>{{ $event->name }} Members</h1>
                <!-- List all users -->
                <ul class="list-disc">
                    @foreach ($users as $user)
                    <li class="text-gray-700 text-sm mb-4">{{ $user->name }}</li>
                    @endforeach
                </ul>

                <!-- Link back to event -->
                <a href="{{ route('events.show', ['event' => $event]) }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full mt-4">Back to Event</a>
            </div>
        </div>
    </div>
</x-app-layout>