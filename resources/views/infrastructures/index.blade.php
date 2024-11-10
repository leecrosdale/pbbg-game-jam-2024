<x-app-layout>

    <x-government-overview :government="$government" />


    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">Infrastructure</h2>

    <div class="flex flex-wrap gap-6 mb-8 px-4 mt-8"> <!-- Add top margin here to create space -->
        @foreach($infrastructures as $infra)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center flex-1 max-w-xs">
                <form method="post" action="{{ route('client.government-infrastructure.update', $infra) }}" >
                    @method('patch')
                    @csrf
                    <img src="{{ url('images/infrastructures/' . \Illuminate\Support\Str::slug($infra->infrastructure->name)) . '.png' }}" class="mb-4 w-16 mx-auto" />
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ $infra->infrastructure->name }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Assigned: {{ $infra->population }}</p>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Generates: {{ $infra->infrastructure->resource_type }}</p>
                    <input type="number" name="population" min="0" class="mt-4 p-2 border rounded w-full text-black" value="{{ $infra->population }}">
                    <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Set</button>
                </form>

                <form method="post" action="{{ route('client.government-infrastructure.upgrade', $infra) }}" >
                    @csrf
                <p class="text-gray-500 dark:text-gray-400 mt-2">Upgrade to level {{ $infra->level + 1 }}: ${{ number_format($infra->upgrade_cost, 2) }}</p>
                    <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Upgrade</button>
                </form>
            </div>

        @endforeach
    </div>

    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 mb-6">Available Infrastructure</h2>

    <div class="flex flex-wrap gap-6 mb-8 px-4 mt-8"> <!-- Add top margin here to create space -->
        @foreach($availableInfrastructures as $infra)
            <form method="post" action="{{ route('client.government-infrastructure.store') }}" class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center flex-1 max-w-xs">
                @csrf
                <input type="hidden" name="infrastructure" value="{{ $infra->uuid }}" />
                <img src="{{ url('images/infrastructures/' . \Illuminate\Support\Str::slug($infra->name)) . '.png' }}" class="mb-4 w-16 mx-auto" />
                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ $infra->name }}</h4>

                <p class="text-gray-500 dark:text-gray-400 mt-2">Cost: ${{ number_format($infra->cost, 2) }}</p>

                <p class="text-gray-500 dark:text-gray-400 mt-2">Generates: {{ $infra->resource_type }}</p>

                <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Buy</button>
            </form>
        @endforeach
    </div>






</x-app-layout>
