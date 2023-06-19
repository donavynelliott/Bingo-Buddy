<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Submit Bingo Square") }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold">{{ $bingoSquare->title }}</h1>
                <p>Approved: {{ $submittedSquare->approved ? 'Yes' : 'No' }}</p>
                <p>Submitted By: {{ $submittedSquare->user->name }}</p>
                @if ($submittedSquare->team != null)
                    <p>Team: {{ $submittedSquare->team->name }}</p>
                @else
                <p>Team: None</p>
                @endif
                <p>Submitted At: {{ $submittedSquare->created_at }}</p>
                @if (substr($submittedSquare->img_link, -4) == '.png' || substr($submittedSquare->img_link, -4) == '.jpg' || substr($submittedSquare->img_link, -5) == '.jpeg')
                    <img src="{{ $submittedSquare->img_link }}" alt="Submission Image" class="max-w-xs">
                @else
                    <p>Image Link: <a href="{{ $submittedSquare->img_link }}">Submitted Image</a></p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>