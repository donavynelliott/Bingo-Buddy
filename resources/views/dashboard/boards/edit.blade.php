<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Edit Bingo Board") }}
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
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">Edit {{ $bingoBoard->name }}</h1>
                    <form id="update-board" action="{{ route('boards.update', ['bingoBoard' => $bingoBoard]) }}" method="POST">
                        <!-- Board Name -->
                        <div class="mb-4">
                            <label for="name" class="sr-only">Name</label>
                            <input type="text" name="name" id="name" placeholder="Board Name" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $bingoBoard->name }}">
                        </div>
                        <!-- Board Type [Classic,Blackout] -->
                        <div class="mb-4">
                            <label for="type" class="sr-only">Type</label>
                            <select name="type" id="type" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                                <option value="classic" @if ($bingoBoard->type === 'classic') selected @endif>Classic</option>
                                <option value="blackout" @if ($bingoBoard->type === 'blackout') selected @endif>Blackout</option>
                            </select>
                        </div>
                        
                        <!-- Create a set of square input boxes based on the size -->
                        <div class="grid grid-cols-{{ $bingoBoard->size }} gap-4">
                            @foreach ($bingoBoard->getBoardData() as $rowKey => $row)
                            @foreach ($row as $colKey => $col)
                            <input type="text" name="square-{{ $rowKey }}-{{ $colKey }}" id="square-{{ $rowKey }}-{{ $colKey }}" placeholder="Square {{ $rowKey }}-{{ $colKey }}" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $col }}">
                            @endforeach
                            @endforeach
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-teal-500 text-white px-4 py-3 rounded font-medium w-full">Update Board</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline script that submits the form as an array -->
    <script>
        $(document).ready(function() {
            $('#update-board').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var squares = serializeSquares(form.serializeArray());
                var data = {
                    _token: '{{ csrf_token() }}',
                    squares: squares
                };
                console.log(data)
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {

                    },
                    error: function(response) {

                    }
                });
            });

            function serializeSquares(data) {
                // Example Data: [{name: 'square-0-0', value: ''}, {name: 'square-0-1', value: ''}, {name: 'square-0-2', value: ''}, {name: 'square-1-0', value: ''}, {name: 'square-1-1', value: ''}, etc...]
                // Example submission: [ ['square 1', 'square 2', 'square 3'], ['square 4', 'square 5', 'square 6'], ['square 7', 'square 8', 'square 9'] ]

                // Create a new array to store the data
                var submission = [];

                for (var i = 0; i < data.length; i++) {
                    // Split the name into an array
                    var name = data[i].name.split('-');
                    if (name === '_token') {
                        continue;
                    }

                    // Get the row number
                    var row = name[1];
                    // Get the column number
                    var col = name[2];
                    // Get the value
                    var value = data[i].value;

                    // If the row doesn't exist in the submission array, create it
                    if (typeof submission[row] === 'undefined') {
                        submission[row] = [];
                    }

                    // Add the value to the submission array
                    submission[row][col] = value;
                }

                // Return the submission array
                return submission;
            }
        });
    </script>

</x-app-layout>