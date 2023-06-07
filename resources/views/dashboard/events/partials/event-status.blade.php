@php
use App\Enums\EventStatus;
@endphp

@if( $event->status->is(EventStatus::NotStarted) )
<p class="text-gray-700 text-sm mb-4">Status: Not Started</p>
@elseif( $event->status->is(EventStatus::InProgress) )
<p class="text-gray-700 text-sm mb-4">Status: In Progress</p>
@elseif( $event->status->is(EventStatus::Closed) )
<p class="text-gray-700 text-sm mb-4">Status: Closed</p>
@else
<p class="text-gray-700 text-sm mb-4">Status: Completed</p>
@endif