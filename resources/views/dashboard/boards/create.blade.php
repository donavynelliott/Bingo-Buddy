<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Board') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form title -->
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">Create Bingo Board</h1>
                    <!-- Form for creating a new event -->
                    <form action="{{ route('boards.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="sr-only">Name</label>
                            <input type="text" name="name" id="name" placeholder="Board Name" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
                        </div>
                        <div class="mb-4">
                            <!-- Choose between 3x3, 4x4, 5x5, or 6x6 -->
                            <label for="size" class="sr-only">Size</label>
                            <select name="size" id="size" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                                <option value="3">3x3</option>
                                <option value="4">4x4</option>
                                <option value="5">5x5</option>
                                <option value="6">6x6</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="bg-teal-500 text-white px-4 py-3 rounded font-medium w-full">Create Board</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>