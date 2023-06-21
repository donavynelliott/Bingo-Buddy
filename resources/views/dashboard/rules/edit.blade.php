<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Edit " . $event->name . " Rules") }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="text-2xl font-bold p-6">Edit Event Rules</h2>
                <form action="{{ route('event-rules.update', ['event' => $event]) }}" method="POST">
                    @csrf
                    <!-- Start Date/Time -->
                    <div class="p-6">
                        <x-input-label for="start_date" :value="__('Start Date/Time')" />
                        <input type="datetime-local" name="start_date" id="start_date" class="block mt-1 w-full" value="{{ $eventRules->start_date }}" required autofocus>
                    </div>
                    <!-- End Date/Time -->
                    <div class="p-6">
                        <x-input-label for="end_date" :value="__('End Date/Time')" />
                        <input type="datetime-local" name="end_date" id="end_date" class="block mt-1 w-full" value="{{ $eventRules->end_date }}" required autofocus>
                    </div>

                    <!-- End condition -->
                    <div class="p-6 text-gray-900">
                        <x-input-label for="end_condition" :value="__('End Condition')" />
                        <select name="end_condition" id="end_condition" class="block mt-1 w-full">
                            <option value="end_date" {{ $eventRules->end_condition === 'end_date' ? 'selected' : '' }}>End Date</option>
                            <option value="all_boards_completed" {{ $eventRules->end_condition === 'all_boards_completed' ? 'selected' : '' }}>All Boards Completed</option>
                        </select>
                    </div>
                    <!-- Max Users -->
                    <div class="p-6 text-gray-900">
                        <x-input-label for="max_users" :value="__('Max Users')" />
                        <x-text-input id="max_users" class="block mt-1 w-full" type="number" name="max_users" :value="$eventRules->max_users" required autofocus />
                    </div>
                    <!-- Public/Private Entry -->
                    <div class="p-6 text-gray-900">
                        <x-input-label for="public" :value="__('Public Entry')" />
                        <select name="public" id="public_entry" class="block mt-1 w-full">
                            <option value="1" {{ $eventRules->public == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $eventRules->public != 1 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <!-- Teams -->
                    <div class="p-6 text-gray-900">
                        <x-input-label for="teams" :value="__('Teams')" />
                        <select name="teams" id="teams" class="block mt-1 w-full">
                            <option value="1" {{ $eventRules->teams == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $eventRules->teams != 1 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <!-- Submit Button -->
                    <div class="p-6 text-gray-900">
                        <button type="submit" class="bg-orange-600 text-white px-4 py-3 rounded font-medium w-full">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>