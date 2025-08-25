<div class="mb-8">
    <!-- Header with Government Name -->
    <div class="text-center mb-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $government->name }}</h1>
        <p class="text-gray-600 text-lg">Civilization Management Dashboard</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Population Card -->
        <div class="stat-card rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Population</p>
                    <p class="text-3xl font-bold">{{ number_format($government->population) }}</p>
                    <p class="text-white/70 text-sm">
                        <i class="fas fa-users mr-1"></i>
                        Available: {{ number_format($government->available_population) }}
                    </p>
                </div>
                <div class="text-4xl text-white/30">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            @if($government->calculatePopulationChange() > 0)
                <div class="mt-3 text-sm text-green-200">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +{{ $government->calculatePopulationChange() }} next tick
                </div>
            @endif
        </div>

        <!-- Overall Rating Card -->
        <div class="stat-card rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Overall Rating</p>
                    <p class="text-3xl font-bold">{{ number_format($government->overall, 1) }}</p>
                    <p class="text-white/70 text-sm">
                        <i class="fas fa-star mr-1"></i>
                        Civilization Score
                    </p>
                </div>
                <div class="text-4xl text-white/30">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <!-- Money Card -->
        <div class="stat-card rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Treasury</p>
                    <p class="text-3xl font-bold">${{ number_format($government->money) }}</p>
                    <p class="text-white/70 text-sm">
                        <i class="fas fa-coins mr-1"></i>
                        Interest: ${{ number_format($government->calculateInterestAmount()) }}
                    </p>
                </div>
                <div class="text-4xl text-white/30">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <!-- Season Card -->
        <div class="stat-card rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Season</p>
                    <p class="text-2xl font-bold">{{ strtoupper((\App\Models\GameSetting::where('name', 'season')->first()->value)) }}</p>
                    <p class="text-white/70 text-sm">
                        <i class="fas fa-leaf mr-1"></i>
                        {{ \App\Enums\Season::from(\App\Models\GameSetting::where('name', 'season')->first()->value)->effect() }}
                    </p>
                </div>
                <div class="text-4xl text-white/30">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>
        </div>

        <!-- Turn Card -->
        <div class="stat-card rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Turn</p>
                    <p class="text-3xl font-bold">{{ \App\Models\GameSetting::where('name', 'turn')->first()->value }}</p>
                    <p class="text-white/70 text-sm">
                        <i class="fas fa-clock mr-1"></i>
                        {{ strtoupper((\App\Models\GameSetting::where('name', 'turn_state')->first()->value)) }}
                    </p>
                </div>
                <div class="text-4xl text-white/30">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <x-countdown></x-countdown>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 font-medium">Quick Actions:</span>
                <a href="{{ route('client.government-infrastructure.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Build Infrastructure
                </a>
                <a href="{{ route('policies.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-cog mr-2"></i>Enact Policy
                </a>
                <a href="{{ route('reports.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>View Reports
                </a>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Last updated: {{ now()->format('H:i:s') }}
            </div>
        </div>
    </div>
</div>
