@php
use App\Enums\EventStatus;
@endphp

<div class="p-6 text-gray-900">
    <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $event->name }}</h1>
    <p class="text-gray-700 text-sm mb-4">Created by {{ $event->user->name }} on {{ $event->created_at->format('F jS, Y') }}</p>

    <!-- Display the Event Status -->
    @include('dashboard.events.partials.event-status', ['event' => $event])

    @if ($event->user_id == auth()->id() && $event->status->is(EventStatus::Setup))
    <a id="edit-event-button" href="{{ route('events.edit', ['event' => $event]) }}" class="bg-indigo-500 text-white px-4 py-3 rounded font-medium w-full">Edit Event</a>
    <a id="open-event-button" href="#" class="bg-green-600 text-white px-4 py-3 rounded font-medium w-full">Open Event</a>
    <!-- Open Event Warning -->
    <div id="event-warning" class="mt-4 text-md text-gray-600 hidden">
        <p class="text-3xl font-bold text-red-600">Warning!</p>
        <p>Opening the event will allow users to join.</p>
        <p class="font-bold text-xl py-3">You will not be able to edit the event rules or bingo boards after opening the event. Any boards with empty squares will be filled with free squares.</p>
        <p>Are you sure you want to open the event?</p>
        <form action="{{ route('events.open', ['event' => $event]) }}" method="POST" class="mt-4">
            @csrf
            <p>
                <button type="submit" id="event-warning-yes-button" class="bg-green-600 text-white px-4 py-3 rounded font-medium">Yes, Open Event</button>
                <button type="button" id="event-warning-no-button" class="bg-red-600 text-white px-4 py-3 rounded font-medium">No, Keep Event in Setup</button>
            </p>
        </form>
    </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $('#open-event-button').click(function(e) {
            e.preventDefault();

            // Show the warning
            $('#event-warning').removeClass('hidden');

            // Hide the open event button
            $('#open-event-button').addClass('hidden');

            // Hide the edit event button
            $('#edit-event-button').addClass('hidden');

            // Disable the Yes button for 5 seconds
            $('#event-warning-yes-button').prop('disabled', true);
            $('#event-warning-yes-button').removeClass('bg-green-600');
            $('#event-warning-yes-button').addClass('bg-gray-200');
            $('#event-warning-yes-button').text("Wait...");

            setTimeout(function() {
                $('#event-warning-yes-button').prop('disabled', false);
                $('#event-warning-yes-button').removeClass('bg-gray-200');
                $('#event-warning-yes-button').addClass('bg-green-600');
                $('#event-warning-yes-button').text("Yes, Open Event");
            }, 5000);
        });

        $('#event-warning-no-button').click(function(e) {
            e.preventDefault();

            // Hide the warning
            $('#event-warning').addClass('hidden');

            // Show the open event button
            $('#open-event-button').removeClass('hidden');

            // Show the edit event button
            $('#edit-event-button').removeClass('hidden');
        });
    });
</script>