@php
use App\Enums\EventStatus;
@endphp

@if( $event->status->is(EventStatus::Setup) )
<p class="text-gray-700 text-sm mb-4">Status: Setup</p>
@elseif( $event->status->is(EventStatus::InProgress) )
<p class="text-gray-700 text-sm mb-4">Status: In Progress</p>
@elseif( $event->status->is(EventStatus::Closed) )
<p class="text-gray-700 text-sm mb-4">Status: Closed</p>
@elseif ( $event->status->is(EventStatus::Open) )
<p class="text-gray-700 text-sm mb-4">Status: Open to Join</p>
@else
<p class="text-gray-700 text-sm mb-4">Status: Completed</p>
@endif