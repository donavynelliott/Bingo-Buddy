<div class="p-6 text-gray-900">
    <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $event->name }}</h1>
    <p class="text-gray-700 text-sm mb-4">Created by {{ $event->user->name }} on {{ $event->created_at->format('F jS, Y') }}</p>

    <!-- Display the Event Status -->
    @include('dashboard.events.partials.event-status', ['event' => $event])

    <!-- Edit button if owner -->
    @if ($event->user_id == auth()->id())
    <div class="p-6 text-gray-900">
        <a href="{{ route('events.edit', ['event' => $event]) }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Edit Event</a>
    </div>
    @endif
</div>