<div class="max-w-7xl sm:px-6 lg:px-8 mx-auto">
    <!-- Success/Error -->
    @if (session('success'))
    <div class=" bg-green-500 rounded-lg mt-3 text-white text-center py-4">
        {{ session('success') }}
    </div>
    @elseif ($errors->any())
    <div class="bg-red-500 rounded-lg mt-3 text-white text-center py-4">
        {{ $errors->first() }}
    </div>
    @endif
</div>