@php
use App\Enums\EventStatus;
@endphp
<div class="p-6 text-gray-900">
    <h2 class="text-2xl text-gray-900 font-bold mb-4">Bingo Boards</h2>

    <!-- Create boards button if owner -->
    @if ($event->user_id == auth()->id() && $event->status->is(EventStatus::Setup))
    <a href="{{ route('boards.create') }}" class="bg-pink-500 text-white px-4 py-3 rounded font-medium w-full">Create New Board</a>
    @endif

    @if ($bingoBoards->isEmpty())
    <p class="text-gray-700 text-sm mt-4">No boards found, Edit Event to attach boards.</p>
    @endif

    <div class="grid grid-cols-3 gap-4 my-6">

        @foreach ($bingoBoards as $bingoBoard)
        <div class="bg-white hover:bg-gray-100 border-pink-500 border overflow-hidden shadow-md sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- If user is in event, show a link to route('submissions.board') -->
                @if ( $event->status->is(EventStatus::InProgress) && $event->users->contains(auth()->user()) )
                <a href="{{ route('submissions.board', ['event' => $event, 'bingoBoard' => $bingoBoard]) }}" class="text-gray-900 font-bold mb-4">
                    <h3 class="text-xl text-rose-600 font-bold mb-4">{{ $bingoBoard->name }}</h3>
                </a>
                @else
                <a href="{{ route('boards.show', ['bingoBoard' => $bingoBoard]) }}" class="text-gray-900 font-bold mb-4">
                    <h3 class="text-xl text-rose-600 font-bold mb-4">{{ $bingoBoard->name }}</h3>
                </a>
                @endif
                <p class="text-gray-700 text-sm mb-4">Size: {{ $bingoBoard->size }}x{{ $bingoBoard->size }}</p>
                <p class="text-gray-700 text-sm mb-4">Type: {{ ucfirst($bingoBoard->type) }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>