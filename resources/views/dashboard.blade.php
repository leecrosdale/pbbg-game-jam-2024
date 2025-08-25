<x-app-layout>
    <x-government-overview :government="$government" />

    <!-- Main Dashboard Content -->
    <div class="space-y-8">
        <!-- Sector Management Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-pie mr-3"></i>
                    Sector Management
                </h2>
                <p class="text-blue-100 mt-1">Manage your civilization's core sectors</p>
            </div>
            
            <div class="p-6">
                <!-- Sector Levels Display -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @php
                        $stats = [
                            'economy' => $government->calculateSectorChange('economy'),
                            'health' => $government->calculateSectorChange('health'),
                            'safety' => $government->calculateSectorChange('safety'),
                            'education' => $government->calculateSectorChange('education'),
                        ];
                    @endphp
                    @foreach ($stats as $name => $stat)
                        <div class="sector-card rounded-xl p-6 card-hover">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-{{ $name === 'economy' ? 'dollar-sign' : ($name === 'health' ? 'heartbeat' : ($name === 'safety' ? 'shield-alt' : 'graduation-cap')) }} text-2xl"></i>
                                </div>
                                <div class="text-right">
                                    <p class="text-white/80 text-sm font-medium">{{ ucfirst($name) }}</p>
                                    <p class="text-3xl font-bold">{{ number_format($government->{$name}, 1) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/70 text-sm">Change</span>
                                <span class="text-2xl font-bold {{ $stat['change'] >= 0 ? 'text-green-200' : 'text-red-200' }}">
                                    {{ $stat['icon'] }}{{ number_format($stat['change'], 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Population Allocation Form -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users-cog mr-2 text-blue-500"></i>
                        Population Allocation
                    </h3>
                    <form action="{{ route('client.government.population.update') }}" method="POST" class="space-y-6">
                        @method('patch')
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach (['economy', 'health', 'safety', 'education'] as $sector)
                                <div class="space-y-2">
                                    <label for="{{ $sector }}" class="block text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-{{ $sector === 'economy' ? 'dollar-sign' : ($sector === 'health' ? 'heartbeat' : ($sector === 'safety' ? 'shield-alt' : 'graduation-cap')) }} mr-2 text-blue-500"></i>
                                        {{ ucfirst($sector) }}
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            id="{{ $sector }}" 
                                            name="{{ $sector }}" 
                                            value="{{ $government->{$sector . '_population'} }}" 
                                            min="0" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="0"
                                        >
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-400 text-sm">people</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                Update Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Infrastructure Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-building mr-3"></i>
                    Infrastructure
                </h2>
                <p class="text-green-100 mt-1">Manage your civilization's buildings and facilities</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($government->government_infrastructures as $infra)
                        <div class="infrastructure-card rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ $infra->infrastructure->type === 'HOUSING' ? 'home' : ($infra->infrastructure->type === 'FOOD' ? 'utensils' : ($infra->infrastructure->type === 'ELECTRICITY' ? 'bolt' : ($infra->infrastructure->type === 'MEDICINE' ? 'pills' : 'tshirt'))) }} text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold mb-2">{{ $infra->infrastructure->name }}</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Level:</span>
                                        <span class="font-bold">{{ $infra->level }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Assigned:</span>
                                        <span class="font-bold">{{ $infra->population }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Production:</span>
                                        <span class="font-bold">{{ number_format($infra->next_tick, 1) }}</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('client.government-infrastructure.index') }}" class="block w-full bg-white/20 hover:bg-white/30 text-white py-2 rounded-lg transition-colors text-center">
                                        <i class="fas fa-cog mr-1"></i>Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Resources Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-red-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-boxes mr-3"></i>
                    Resources
                </h2>
                <p class="text-pink-100 mt-1">Monitor your civilization's resource levels</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    @foreach($government->government_resources as $resource)
                        <div class="resource-card rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ $resource->resource->name === 'food' ? 'utensils' : ($resource->resource->name === 'electricity' ? 'bolt' : ($resource->resource->name === 'medicine' ? 'pills' : ($resource->resource->name === 'happiness' ? 'smile' : 'tshirt'))) }} text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold mb-2 capitalize">{{ $resource->resource->name }}</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Amount:</span>
                                        <span class="font-bold">{{ number_format($resource->amount) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Usage:</span>
                                        <span class="font-bold">{{ number_format($resource->population_usage ?? 0) }}</span>
                                    </div>
                                </div>
                                <!-- Progress bar for resource capacity -->
                                @php
                                    $capacity = config("game.resources.storage_capacity.{$resource->resource->name}", 1000);
                                    $percentage = min(100, ($resource->amount / $capacity) * 100);
                                @endphp
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-white/70 mb-1">
                                        <span>Storage</span>
                                        <span>{{ number_format($percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
