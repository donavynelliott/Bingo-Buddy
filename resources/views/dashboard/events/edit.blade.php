<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($event->name) }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Event Details Form -->
                <form method="POST" action="{{ route('events.update', ['event' => $event]) }}">
                    @csrf
                    <!-- Name field -->
                    <div class="p-6 text-gray-900">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$event->name" required autofocus />
                    </div>
                    <!-- Display checkbox for all boards owned by user -->
                    <div class="p-6 text-gray-900">
                        @foreach ($boards as $board)
                        <input type="checkbox" name="bingo_board_ids[]" value="{{ $board->id }}" @if ($event->bingoBoards->contains($board)) checked @endif>
                        <label for="boards">{{ $board->name }}</label><br>
                        @endforeach
                    </div>
                    <!-- Submit button -->
                    <div class="p-6 text-gray-900">
                        <button type="submit" class="bg-teal-500 text-white px-4 py-3 rounded font-medium w-full">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>