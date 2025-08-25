<x-app-layout>
    <x-government-overview :government="$government" />

    <!-- Reports and Analytics -->
    <div class="space-y-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Population</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['population']['total']) }}</p>
                        <p class="text-blue-200 text-sm">+{{ $stats['population']['growth_rate'] }} next tick</p>
                    </div>
                    <div class="text-4xl text-blue-200">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Treasury</p>
                        <p class="text-3xl font-bold">${{ number_format($stats['financial']['current_money']) }}</p>
                        <p class="text-green-200 text-sm">{{ number_format($stats['financial']['interest_rate'] * 100, 1) }}% interest rate</p>
                    </div>
                    <div class="text-4xl text-green-200">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Infrastructure</p>
                        <p class="text-3xl font-bold">{{ $stats['infrastructure']['total'] }}</p>
                        <p class="text-purple-200 text-sm">Level {{ $stats['infrastructure']['total_level'] }}</p>
                    </div>
                    <div class="text-4xl text-purple-200">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">Overall Score</p>
                        <p class="text-3xl font-bold">{{ number_format($government->overall, 1) }}</p>
                        <p class="text-orange-200 text-sm">Civilization Rating</p>
                    </div>
                    <div class="text-4xl text-orange-200">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Analysis -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-red-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Resource Analysis
                </h2>
                <p class="text-pink-100 mt-1">Detailed breakdown of your resources</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stats['resources'] as $resourceName => $resource)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-800 capitalize">{{ $resourceName }}</h3>
                                <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-red-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-{{ $resourceName === 'food' ? 'utensils' : ($resourceName === 'electricity' ? 'bolt' : ($resourceName === 'medicine' ? 'pills' : ($resourceName === 'happiness' ? 'smile' : 'tshirt'))) }} text-white text-sm"></i>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Amount:</span>
                                    <span class="font-semibold">{{ number_format($resource['amount']) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Capacity:</span>
                                    <span class="font-semibold">{{ number_format($resource['capacity']) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Usage:</span>
                                    <span class="font-semibold">{{ number_format($resource['usage']) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Value:</span>
                                    <span class="font-semibold">${{ number_format($resource['value'], 2) }}</span>
                                </div>
                                
                                <!-- Progress bar -->
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Storage</span>
                                        <span>{{ number_format($resource['percentage'], 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-pink-500 to-red-600 h-2 rounded-full transition-all duration-300" style="width: {{ $resource['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sector Performance -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-line mr-3"></i>
                    Sector Performance
                </h2>
                <p class="text-blue-100 mt-1">Analysis of your sector efficiency</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($stats['sectors'] as $sectorName => $sector)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-800 capitalize">{{ $sectorName }}</h3>
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-{{ $sectorName === 'economy' ? 'dollar-sign' : ($sectorName === 'health' ? 'heartbeat' : ($sectorName === 'safety' ? 'shield-alt' : 'graduation-cap')) }} text-white text-sm"></i>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Level:</span>
                                    <span class="font-semibold">{{ number_format($sector['level'], 1) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Population:</span>
                                    <span class="font-semibold">{{ number_format($sector['population']) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Efficiency:</span>
                                    <span class="font-semibold">{{ number_format($sector['efficiency'], 3) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Infrastructure Breakdown -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-industry mr-3"></i>
                    Infrastructure Breakdown
                </h2>
                <p class="text-green-100 mt-1">Detailed infrastructure analysis</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stats['infrastructure']['by_type'] as $type => $data)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', $type) }}</h3>
                                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-{{ $type === 'HOUSING' ? 'home' : ($type === 'FOOD' ? 'utensils' : ($type === 'ELECTRICITY' ? 'bolt' : ($type === 'MEDICINE' ? 'pills' : 'tshirt'))) }} text-white text-sm"></i>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Count:</span>
                                    <span class="font-semibold">{{ $data['count'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Level:</span>
                                    <span class="font-semibold">{{ $data['total_level'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Population:</span>
                                    <span class="font-semibold">{{ number_format($data['total_population']) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Production:</span>
                                    <span class="font-semibold">{{ number_format($data['total_production'], 1) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Financial Analysis -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-calculator mr-3"></i>
                    Financial Analysis
                </h2>
                <p class="text-yellow-100 mt-1">Economic performance metrics</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Current Treasury</h3>
                        <p class="text-3xl font-bold text-green-600">${{ number_format($stats['financial']['current_money']) }}</p>
                        <p class="text-sm text-gray-600 mt-1">Available funds</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Resource Value</h3>
                        <p class="text-3xl font-bold text-blue-600">${{ number_format($stats['financial']['total_resource_value'], 2) }}</p>
                        <p class="text-sm text-gray-600 mt-1">Total resource worth</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Maintenance Cost</h3>
                        <p class="text-3xl font-bold text-red-600">${{ number_format($stats['financial']['maintenance_cost'], 2) }}</p>
                        <p class="text-sm text-gray-600 mt-1">Per turn</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
