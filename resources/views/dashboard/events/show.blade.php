@php
use App\Enums\EventStatus;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($event->name) }}
        </h2>
    </x-slot>

    <!-- Success/Error -->
    @if (session('success'))
    <div class="bg-green-500 p-4 rounded-lg mb-6 text-white text-center">
        {{ session('success') }}
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- A grid that has 2 columns -->
                <div class="grid grid-cols-2">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $event->name }}</h1>
                        <p class="text-gray-700 text-sm mb-4">Created by {{ $event->user->name }} on {{ $event->created_at->format('F jS, Y') }}</p>

                        <!-- Edit button if owner -->
                        @if ($event->user_id == auth()->id())
                        <div class="p-6 text-gray-900">
                            <a href="{{ route('events.edit', ['event' => $event]) }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Edit Event</a>
                        </div>
                        @endif
                    </div>

                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl text-gray-900 font-bold mb-4">Event Rules</h2>
                        <p class="text-gray-700 text-sm mb-4">Start Date: {{ $event->rules->start_date->format('F jS, Y') }}</p>
                        <p class="text-gray-700 text-sm mb-4">End Date: {{ $event->rules->end_date->format('F jS, Y') }}</p>
                        <p class="text-gray-700 text-sm mb-4">End Condition: {{ $event->rules->end_condition ? "End Date" : "Board Completion" }}</p>
                        <p class="text-gray-700 text-sm mb-4">Max Users: {{ $event->rules->max_users }}</p>
                        <p class="text-gray-700 text-sm mb-4">Type: {{ $event->rules->public ? "Public" : "Private" }}</p>
                        <p class="text-gray-700 text-sm mb-4">Teams: {{ $event->rules->teams ? "Teams" : "Individuals" }}</p>

                        <!-- Edit button if owner -->
                        @if ($event->user_id == auth()->id())
                        <div class="p-6 text-gray-900">
                            <a href="{{ route('event-rules.edit', ['event' => $event]) }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Edit Rules</a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl text-gray-900 font-bold mb-4">Event Members</h2>
                    @php
                        $users = $event->users;
                        $userCount = $users->count();
                    @endphp
                    <!-- Show 10 users -->
                    <div class="grid grid-cols-5 gap-4">
                        <ul class="list-disc">
                            @foreach ($users->take(10) as $user)
                            <li class="text-gray-700 text-sm mb-4">{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($userCount > 10)
                        <a href="{{ route('events.members', ['event' => $event]) }}" class="text-gray-700 text-sm mb-4">And {{ $userCount - 10 }} more...</a>
                    @endif

                    @if ($event->status->is(EventStatus::NotStarted) && $event->rules->teams)
                    <div class="p-6 text-gray-900">
                        <a href="{{ route('events.team-setup', ['event' => $event]) }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Team Setup</a>
                    </div>
                    @endif
                </div>

                <!-- Related Bingo Boards -->
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl text-gray-900 font-bold mb-4">Bingo Boards</h2>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach ($bingoBoards as $bingoBoard)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <a href="{{ route('boards.show', ['bingoBoard' => $bingoBoard]) }}" class="text-gray-900 font-bold mb-4">
                                    <h3 class="text-xl text-gray-900 font-bold mb-4">{{ $bingoBoard->name }}</h3>
                                </a>
                                <p class="text-gray-700 text-sm mb-4">Size: {{ $bingoBoard->size }}x{{ $bingoBoard->size }}</p>
                                <p class="text-gray-700 text-sm mb-4">Type: {{ ucfirst($bingoBoard->type) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
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