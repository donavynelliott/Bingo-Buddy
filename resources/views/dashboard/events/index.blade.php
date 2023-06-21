<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <table class="border-collapse w-full">
                    <thead>
                        <th class="border border-slate-400 bg-gray-100 p-2">Event</th>
                        <th class="border border-slate-400 bg-gray-100 p-2">Status</th>
                        <th class="border border-slate-400 bg-gray-100 p-2">Start Date</th>
                        <th class="border border-slate-400 bg-gray-100 p-2">End Date</th>
                        <th class="border border-slate-400 bg-gray-100 p-2">End Condition</th>
                        <th class="border border-slate-400 bg-gray-100 p-2">Participants</th>
                        <th class="border border-slate-400 bg-gray-100 p-2">Host</th>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                        <tr class="border hover:bg-slate-100 border-slate-300 text-center">
                            <td class="text-indigo-600 font-bold py-6"><a href="{{ route('events.show', ['event' => $event]) }}">{{ $event->name }}</a></td>
                            <td class="text-green-400 font-bold">Open</td>
                            <td class="p-2">{{ date_format(date_create($event->start_date), 'F j, Y') }}</td>
                            <td class="p-2">{{ $event->end_condition == 'end_date' ? date_format(date_create($event->end_date), 'F j, Y') : '' }}</td>
                            <td class="p-2">{{ $event->end_condition }}</td>
                            <td class="p-2">
                                {{ $event->users_count . "/" . $event->max_users }}
                                <a href="{{ route('events.show', ['event' => $event]) }}" class="ml-2 bg-indigo-600 text-white p-2 rounded-sm font-bold">Join</a>
                            </td>
                            <td class="p-2">{{ $event->host_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>