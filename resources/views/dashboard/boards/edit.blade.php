<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Edit Bingo Board") }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Event Details -->
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">Edit {{ $bingoBoard->name }}</h1>
                    <form id="update-board" onsubmit="return validateForm()" action="{{ route('boards.update', ['bingoBoard' => $bingoBoard]) }}" method="POST">
                        @csrf
                        <!-- Board Name -->
                        <div class="mb-4">
                            <label for="name" class="sr-only">Name</label>
                            <input type="text" name="name" id="name" placeholder="Board Name" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $bingoBoard->name }}">
                        </div>
                        <!-- Board Type [Classic,Blackout] -->
                        <div class="mb-4">
                            <label for="type" class="sr-only">Type</label>
                            <select name="type" id="type" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                                <option value="classic" @if ($bingoBoard->type === 'classic') selected @endif>Classic</option>
                                <option value="blackout" @if ($bingoBoard->type === 'blackout') selected @endif>Blackout</option>
                            </select>
                        </div>

                        <!-- Warning that empty squares will be turned into free squares -->
                        <div class="mb-4">
                            <p class="text-xl text-red-500 font-bold">Warning: Empty squares will be turned into free squares once an event starts.</p>
                        </div>

                        <!-- Create a set of square input boxes based on the size -->
                        <div class="grid grid-cols-{{ $bingoBoard->size }} divide-x divide-y border border-black h-full">
                            @php
                            $squares = $bingoBoard->bingoSquares()->get();
                            @endphp

                            @for ($i = 0; $i < pow($bingoBoard->size, 2); $i++)
                                <div class="bg-white overflow-hidden border-slate-200 aspect-square flex justify-center items-center">
                                    <div class="text-gray-900 text-center">
                                        <!-- Check if one of the squares position property is equal to $i, and assign it to a var -->
                                        @if ($squares->contains('position', $i))
                                        @php
                                        $square = $squares->where('position', $i)->first();
                                        @endphp
                                        <input type="text" name="squares[{{$i}}]" id="square-{{ $i }}" placeholder="Square {{ $i + 1 }}" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $square->title }}">
                                        @else
                                        <input type="text" name="squares[{{$i}}]" id="square-{{ $i }}" placeholder="Square {{ $i + 1}}" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                                        @endif
                                    </div>
                                </div>
                                @endfor
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-pink-500 text-white px-4 py-3 rounded font-medium w-full">Update Board</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>