<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form title -->
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">Create Event</h1>
                    <!-- Form for creating a new event -->
                    <form action="{{ route('events.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="sr-only">Name</label>
                            <input type="text" name="name" id="name" placeholder="Event Name" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-500 text-white px-4 py-3 rounded font-medium w-full">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>