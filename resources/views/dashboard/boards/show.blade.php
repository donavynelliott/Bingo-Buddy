<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($bingoBoard->name) }}
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
                <!-- Event Details -->
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $bingoBoard->name }}</h1>
                    <!-- Board Properties -->
                    <div class="mb-4">
                        <p class="text-gray-700">Size: {{ $bingoBoard->size }}x{{ $bingoBoard->size }}</p>
                        <p class="text-gray-700">Type: {{ $bingoBoard->type }}</p>
                    </div>

                    <!-- Create a set of square input boxes based on the size -->
                    <div class="grid grid-cols-{{ $bingoBoard->size }} gap-4">
                        @foreach ($bingoBoard->getBoardData() as $rowKey => $row)
                        @foreach ($row as $colKey => $col)
                        <!-- Display each column as a card and not as an input-->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <p class="text-gray-700">Square {{ $rowKey }}-{{ $colKey }}</p>
                                <p class="text-gray-700">{{ $col }}</p>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>

                    <!-- Edit Button if owner -->
                    @if ($bingoBoard->user_id === Auth::user()->id)
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('boards.edit', ['bingoBoard' => $bingoBoard]) }}" class="bg-teal-500 text-white px-4 py-3 rounded font-medium w-full">Edit Board</a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>