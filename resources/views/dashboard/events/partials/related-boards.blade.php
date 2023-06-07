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