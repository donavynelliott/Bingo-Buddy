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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>