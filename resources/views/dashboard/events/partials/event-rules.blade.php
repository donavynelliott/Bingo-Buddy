@php
use App\Enums\EventStatus;
@endphp

<div class="p-6 text-gray-900">
    <h2 class="text-2xl text-gray-900 font-bold mb-4">Event Rules</h2>
    <p class="text-gray-700 text-sm mb-4">Start Date: {{ $event->rules->start_date->format('F jS, Y') }}</p>
    <p class="text-gray-700 text-sm mb-4">End Date: {{ $event->rules->end_date->format('F jS, Y') }}</p>
    <p class="text-gray-700 text-sm mb-4">End Condition: {{ $event->rules->end_condition ? "End Date" : "Board Completion" }}</p>
    <p class="text-gray-700 text-sm mb-4">Max Users: {{ $event->rules->max_users }}</p>
    <p class="text-gray-700 text-sm mb-4">Type: {{ $event->rules->public ? "Public" : "Private" }}</p>
    <p class="text-gray-700 text-sm mb-4">Teams: {{ $event->rules->teams ? "Teams" : "Individuals" }}</p>

    <!-- Edit button if owner -->
    @if ($event->user_id == auth()->id() && $event->status->is(EventStatus::Setup))
    <div class="py-6 text-gray-900">
        <a href="{{ route('event-rules.edit', ['event' => $event]) }}" class="bg-orange-600 text-white px-4 py-3 rounded font-medium w-full">Edit Rules</a>
    </div>
    @endif
</div>