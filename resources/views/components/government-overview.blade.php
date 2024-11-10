<div class="flex justify-center mb-3"> <!-- Add bottom margin here if desired -->
    <div class="flex w-full space-x-6 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Population of {{ $government->name }}</h3>
            <p class="text-xl font-bold text-teal-500">{{ $government->available_population }} / {{ $government->population }}</p>
            <p>Incoming: +{{ $government->calculatePopulationChange() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Overall Rating</h3>
            <p class="text-xl font-bold text-teal-500">{{ $government->overall }}</p>
{{--            <p>Change Next Tick {{ $government-> }}</p>--}}
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Money</h3>
            <p class="text-xl font-bold text-yellow-500">${{ number_format($government->money) }}</p>
            <p>(Change Next Tick: ${{ number_format($government->calculateInterestAmount()) }})</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Season</h3>
            <p class="text-xl font-bold text-teal-500">{{ strtoupper((\App\Models\GameSetting::where('name', 'season')->first()->value)) }}</p>
            <p>{{ \App\Enums\Season::from(\App\Models\GameSetting::where('name', 'season')->first()->value)->effect() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Turn</h3>
            <p class="text-xl font-bold text-teal-500">{{ strtoupper((\App\Models\GameSetting::where('name', 'turn')->first()->value)) }}</p>
            <p>{{ strtoupper((\App\Models\GameSetting::where('name', 'turn_state')->first()->value)) }}</p>
            <x-countdown></x-countdown>
        </div>

    </div>
</div>
