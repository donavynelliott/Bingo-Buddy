@php
use App\Enums\EventStatus;
@endphp

<div class="p-6 text-gray-900">
    @if ($event->rules->teams && $event->teams->count() > 0)
    <h2 class="text-2xl text-gray-900 font-bold mb-4">Event Teams</h2>
    <div class="grid grid-cols-4 gap-4">
        @foreach ($event->teams as $team)
        <div>
            <h2 class="text-1xl text-gray-900 font-bold mb-4">{{ $team->name }}</h2>
            @php
            $users = $team->users;
            $userCount = $users->count();
            @endphp

            <!-- Show 10 users -->
            <ul class="list-disc">
                @foreach ($users->take(10) as $user)
                <li class="text-gray-700 text-sm mb-4">{{ $user->name }}</li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
    @else
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
    @endif

    @if ($event->user_id == auth()->id() && $event->status->is(EventStatus::NotStarted) && $event->rules->teams)
    <div class="p-6 text-gray-900">
        <a href="{{ route('events.team-setup', ['event' => $event]) }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Team Setup</a>
    </div>
    @endif
</div>