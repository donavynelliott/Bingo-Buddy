<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($bingoBoard->name) }}
        </h2>
    </x-slot>

    @include('components.alert')

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
                        @php
                            $squares = $bingoBoard->bingoSquares()->get();
                        @endphp

                        @for ($i = 0; $i < pow($bingoBoard->size, 2); $i++)

                            @php
                                $square = $squares->where('position', $i)->first();
                                $submitted = $submittedSquares->contains('bingo_square_id', $square->id);
                                $submittedSquare = $submitted ? $submittedSquares->where('bingo_square_id', $square->id)->first() : null;
                                $approved = $submittedSquare ? $submittedSquare->approved : false;
                            @endphp
                            @if ($submitted)
                            <a href="{{ route('submissions.show', ['event' => $event, 'bingoSquare' => $square, 'submittedSquare' => $submittedSquare]) }}">
                            @else
                            <a href="{{ route('submissions.create', ['event' => $event, 'bingoSquare' => $square]) }}">
                            @endif

                                <div class="{{ $approved ? 'bg-green-100 hover:bg-green-200' : ($submittedSquare ? 'bg-yellow-50 hover:bg-yellow-100' : 'bg-white hover:bg-gray-50') }} overflow-hidden border-slate-200 aspect-square flex justify-center items-center">
                                    <div class="text-center">
                                        @if ($submitted)
                                            <p class="text-teal-500">{{ $square->title }}</p>
                                            <p class="text-gray-800">{{ !$approved ? '[Pending]' : '' }}</p>
                                        @else
                                            <p class="text-gray-900">
                                                {{ $square->title }}    
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endfor
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>