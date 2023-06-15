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
                <div class="py-4">
                    <h2 class="text-3xl font-bold">Submit Bingo Square</h2>
                </div>

                <div class="py-4">
                    <h3 class="text-2xl">{{ $event->name }}</h3>
                    <p class="text-gray-900">{{ $bingoSquare->title }}</p>
                </div>

                <div class="py-4">
                    <p class="font-bold">
                        You must submit an image link for your square.
                    </p>
                    <p> 
                        You can use a service like 
                        <a href="https://imgur.com/" target="_blank" class="text-teal-500">Imgur</a> or 
                        <a href="https://postimages.org/" target="_blank" class="text-teal-500">PostImages</a> 
                        to upload your image and get a link.
                    </p>
                </div>

                <form method="POST" action="{{ route('submissions.store', ['event' => $event, 'bingoSquare' => $bingoSquare]) }}">
                    @csrf
                    <!-- Img Link -->
                    <div class="mb-4">
                        <label for="img_link" class="sr-only">Image Link</label>
                        <input type="text" name="img_link" id="img_link" placeholder="Image Link" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $bingoSquare->img_link }}">
                    </div>

                    <button type="submit" class="bg-teal-500 text-white px-4 py-3 rounded font-medium w-full">Submit Square</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>