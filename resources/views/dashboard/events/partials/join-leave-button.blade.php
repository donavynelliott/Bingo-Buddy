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