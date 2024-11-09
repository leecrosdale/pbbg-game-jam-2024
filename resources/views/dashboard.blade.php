<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <x-government-overview :government="$government" />

    <!-- Add a margin / gap here -->

    <div class="flex flex-wrap gap-6 mb-8 px-4 mt-8"> <!-- Add top margin here to create space -->
        @foreach($government->government_infrastructures as $infra)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center flex-1 max-w-xs">
                @method('patch')
                @csrf
                <img src="{{ url('images/infrastructures/' . \Illuminate\Support\Str::slug($infra->infrastructure->name)) . '.png' }}" class="mb-4 w-16 mx-auto" />
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ $infra->infrastructure->name }}</h4>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Assigned: {{ $infra->population }}</p>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Upgrade: ${{ number_format($infra->upgrade_cost, 2) }}</p>
            </div>
        @endforeach
    </div>


    <div class="flex flex-wrap gap-6 mb-8 px-4">
        @foreach($government->government_resources as $resource)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center max-w-xs">
                <img src="{{ url('images/resources/' . \Illuminate\Support\Str::slug($resource->resource->name) . '.png') }}" class="mb-4 w-16 mx-auto" />
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">{{ $resource->resource->name }}</h4>
                <p class="text-xl font-bold text-teal-500">{{ $resource->amount }}</p>
            </div>
        @endforeach
    </div>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <h4 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Sector Levels</h4>
        <form action="#" method="POST">
            @csrf
            <div class="flex space-x-6">
                @php
                    $stats = [
                        'economy' => $government->getStatChange('economy'),
                        'health' => $government->getStatChange('health'),
                        'safety' => $government->getStatChange('safety'),
                        'education' => $government->getStatChange('education'),
                    ];
                @endphp
                @foreach ($stats as $name => $stat)
                    <div class="p-6 bg-white dark:bg-gray-700 rounded-lg shadow text-center flex-1">
                        <img src="{{ url('images/sectors/' . $name . '.png') }}" class="w-12 mx-auto mb-4" />
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">{{ ucfirst($name) }}</label>
                        <p class="text-xl font-bold text-teal-500">Change: {{ $stat['icon'] }}{{ $stat['change'] }}</p>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <h4 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Sector Population Allocation</h4>
        <form action="{{ route('client.government.population.update') }}" method="POST">
            @method('patch')
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach (['economy', 'health', 'safety', 'education'] as $sector)
                    <div>
                        <label for="{{ $sector }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($sector) }}</label>
                        <input type="number" id="{{ $sector }}" name="{{ $sector }}" value="{{ $government->{$sector . '_population'} }}" min="0" class="mt-2 p-2 border rounded w-full text-black">
                    </div>
                @endforeach
            </div>
            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Allocation</button>
            </div>
        </form>
    </div>
</x-app-layout>
