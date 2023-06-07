<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($event->name) }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- A grid that has 2 columns -->
                <div class="grid grid-cols-2">
                    @include('dashboard.events.partials.event-details', ['event' => $event])

                    @include('dashboard.events.partials.event-rules', ['event' => $event])
                </div>

                @include('dashboard.events.partials.event-members', ['event' => $event])

                <!-- Related Bingo Boards -->
                @include('dashboard.events.partials.related-boards', ['event' => $event])
                
                @include('dashboard.events.partials.join-leave-button', ['event' => $event])

            </div>
        </div>
    </div>
</x-app-layout>