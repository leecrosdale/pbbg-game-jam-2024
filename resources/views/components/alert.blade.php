{{-- resources/views/components/alert.blade.php --}}

@if (session('status'))
    <div class="mb-4 rounded-md bg-green-50 p-4 dark:bg-green-900">
        <div class="flex">
            <div class="flex-shrink-0">
                <!-- Success Icon -->
                <svg class="h-5 w-5 text-green-400 dark:text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800 dark:text-green-100">
                    {{ session('status') }}
                </p>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 p-4 dark:bg-red-900">
        <div class="flex">
            <div class="flex-shrink-0">
                <!-- Error Icon -->
                <svg class="h-5 w-5 text-red-400 dark:text-red-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-100">There were some errors:</h3>
                <ul class="mt-1 text-sm text-red-700 dark:text-red-200 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
