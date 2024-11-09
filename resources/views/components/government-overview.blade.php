<div class="flex justify-center mb-8"> <!-- Add bottom margin here if desired -->
    <div class="flex w-full max-w-4xl space-x-6 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Population of {{ $government->name }}</h3>
            <p class="text-xl font-bold text-teal-500">{{ $government->available_population }} / {{ $government->population }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Overall Rating</h3>
            <p class="text-xl font-bold text-teal-500">{{ $government->overall }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center flex-1">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Money</h3>
            <p class="text-xl font-bold text-yellow-500">${{ $government->money }}</p>
        </div>
    </div>
</div>
