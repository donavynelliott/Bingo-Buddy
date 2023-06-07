<!-- Success/Error -->
@if (session('success'))
<div class="bg-green-500 p-4 rounded-lg mx-6 mt-3 text-white text-center">
    {{ session('success') }}
</div>
@elseif ($errors->any())
<div class="bg-red-500 p-4 rounded-lg mx-6 mt-3 text-white text-center">
    {{ $errors->first() }}
</div>
@endif