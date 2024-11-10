<x-app-layout>

    <x-government-overview :government="$government" />

    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">Resources</h2>

    <div class="flex flex-wrap gap-6 mb-8 px-4">
        @foreach($resources as $resource)
            <form method="post" action="{{ route('client.government-resources.update', $resource) }}" class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center max-w-xs">
                @csrf
                @method('patch')
                <img src="{{ url('images/resources/' . \Illuminate\Support\Str::slug($resource->resource->name) . '.png') }}" class="mb-4 w-16 mx-auto" />
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">{{ $resource->resource->name }}</h4>
                <p class="text-xl font-bold text-teal-500">Amount: {{ $resource->amount }}</p>
                <p class="text-xl font-bold text-teal-500">Value: ${{ $resource->resource->price }} each</p>
                <input type="number" name="sell" min="0" class="mt-4 p-2 border rounded w-full text-black" value="{{ $resource->amount }}">
                <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Sell</button>
            </form>
        @endforeach
    </div>

    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">Ungathered Resources</h2>

    <div class="flex flex-wrap gap-6 mb-8 px-4">
        @foreach($ungatheredResources as $resource)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center max-w-xs">
                <img src="{{ url('images/resources/' . \Illuminate\Support\Str::slug($resource->name) . '.png') }}" class="mb-4 w-16 mx-auto" />
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">{{ $resource->name }}</h4>
            </div>
        @endforeach
    </div>

</x-app-layout>
