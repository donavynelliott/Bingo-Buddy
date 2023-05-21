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

                    <!-- Trick tailwind css into including grid utlities -->
                    <div class="grid-cols-3 grid-cols-4 grid-cols-5 grid-cols-6" style="display:none;"></div>

                    <!-- Create a set of square input boxes based on the size -->
                    <div class="grid grid-cols-{{ $bingoBoard->size }} divide-x divide-y border border-black h-full">
                        @foreach ($bingoBoard->getBoardData() as $rowKey => $row)
                        @foreach ($row as $colKey => $col)
                        <div class="bg-white overflow-hidden border-slate-200 aspect-square flex justify-center items-center">
                            <div class="text-gray-900 text-center">
                                {{ $col }}
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