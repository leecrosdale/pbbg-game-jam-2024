<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex justify-center mb-6">
        <div class="grid grid-cols-2 gap-4 w-full max-w-xl">
            <!-- Population -->
            <div class="p-4 bg-white dark:bg-black dark:text-white rounded shadow text-center">
                <h2 class="text-lg font-semibold">Population</h2>
                <p class="text-2xl font-bold text-green-600">{{ $government->available_population }} / {{ $government->population }}</p>
            </div>
            <!-- Money -->
            <div class="p-4 bg-white dark:bg-black dark:text-white rounded shadow text-center">
                <h2 class="text-lg font-semibold">Money</h2>
                <p class="text-2xl font-bold text-yellow-600">${{ $government->money }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center mb-6">
        <div class="grid grid-cols-2 gap-4 w-full max-w-xl">
            @foreach($government->government_infrastructures as $infra)
            <div class="p-4 bg-white dark:bg-black dark:text-white rounded shadow text-center">
                <h2 class="text-lg font-semibold">{{ $infra->infrastructure->name }} - {{ $infra->infrastructure->type }}</h2>
                <p class="text-2xl font-bold text-green-600">{{ $infra->level }} * {{ $infra->population }} =  {{ $infra->next_tick }}</p>
                <p class="text-2xl font-bold text-green-600">Upgrade Cost: ${{ $infra->upgrade_cost }}</p>
                <input type="number" id="health" name="{{ $infra->uuid }}" value="{{ $infra->population }}" min="0" class="mt-1 p-2 border rounded w-full">
            </div>
            @endforeach
        </div>
    </div>


    <div class="flex justify-center mb-6">
        <div class="grid grid-cols-2 gap-4 w-full max-w-xl">
            @foreach($government->government_resources as $resource)
                <div class="p-4 bg-white dark:bg-black dark:text-white rounded shadow text-center">
                    <h2 class="text-lg font-semibold">{{ $resource->resource->name }}</h2>
                    <p class="text-2xl font-bold text-green-600">{{ $resource->amount  }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow mb-6 dark:bg-black">
        <h2 class="text-xl font-semibold mb-4 dark:text-white">Sector Levels</h2>
        <form action="#" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="economy" class="block text-sm font-medium text-gray-700 dark:text-white">Economy</label>
                    <p class="text-2xl font-bold text-green-600">{{ floor($government->economy)  }}</p>
                </div>
                <div>
                    <label for="health" class="block text-sm font-medium text-gray-700 dark:text-white">Health</label>
                    <p class="text-2xl font-bold text-green-600">{{ floor($government->health)  }}</p>
                </div>
                <div>
                    <label for="safety" class="block text-sm font-medium text-gray-700 dark:text-white">Safety</label>
                    <p class="text-2xl font-bold text-green-600">{{ floor($government->safety)  }}</p>
                </div>
                <div>
                    <label for="education" class="block text-sm font-medium text-gray-700 dark:text-white">Education</label>
                    <p class="text-2xl font-bold text-green-600">{{ floor($government->education)  }}</p>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Allocation</button>
            </div>
        </form>
    </div>


    <div class="bg-white p-6 rounded shadow mb-6 dark:bg-black">
        <h2 class="text-xl font-semibold mb-4 dark:text-white">Sector Allocation</h2>
        <form action="#" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="economy" class="block text-sm font-medium text-gray-700 dark:text-white">Economy</label>
                    <input type="number" id="economy" name="economy" value="{{ $government->economy_population }}" min="0" class="mt-1 p-2 border rounded w-full">
                </div>
                <div>
                    <label for="health" class="block text-sm font-medium text-gray-700 dark:text-white">Health</label>
                    <input type="number" id="health" name="health" value="{{ $government->health_population }}" min="0" class="mt-1 p-2 border rounded w-full">
                </div>
                <div>
                    <label for="safety" class="block text-sm font-medium text-gray-700 dark:text-white">Safety</label>
                    <input type="number" id="safety" name="safety" value="{{ $government->safety_population }}" min="0" class="mt-1 p-2 border rounded w-full">
                </div>
                <div>
                    <label for="education" class="block text-sm font-medium text-gray-700 dark:text-white">Education</label>
                    <input type="number" id="education" name="education" value="{{ $government->education_population }}" min="0" class="mt-1 p-2 border rounded w-full">
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Allocation</button>
            </div>
        </form>
    </div>


</x-app-layout>
