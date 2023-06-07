<!-- Success/Error -->
@if (session('success'))
<div class="bg-green-500 p-4 rounded-lg mx-6 mt-3 text-white text-center">
    {{ session('success') }}
</div>
@elseif (session('error'))
<div class="bg-red-500 p-4 rounded-lg mx-6 mt-3 text-white text-center">
    {{ session('error') }}
</div>
@endif