<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>

                <!-- Create New Event / New Board -->
                <div class="p-6 text-gray-900">
                    <a href="{{ route('events.create') }}" class="bg-indigo-500 text-white px-4 py-3 rounded font-medium w-full">Create New Event</a>
                    <a href="{{ route('boards.create') }}" class="bg-pink-500 text-white px-4 py-3 rounded font-medium w-full">Create New Board</a>
                </div>


                <!-- Display any events that the user is attached to -->
                @if ($memberEvents->count() > 0)
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">Events you are a member of</h1>
                    <ul>
                        @foreach ($memberEvents as $event)
                        <li><a href="{{ route('events.show', ['event' => $event]) }}">{{ $event->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Display any events the user is the owner of -->
                @if ($ownedEvents->count() > 0)
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">Events you own</h1>
                    <ul>
                        @foreach ($ownedEvents as $event)
                        <li><a href="{{ route('events.show', ['event' => $event]) }}">{{ $event->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>
