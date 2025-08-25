<x-app-layout>
    <x-government-overview :government="$government" />

    <!-- Resources Management -->
    <div class="space-y-8">
        <!-- Current Resources -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-red-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-boxes mr-3"></i>
                    Your Resources
                </h2>
                <p class="text-pink-100 mt-1">Manage and trade your civilization's resources</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    @foreach($resources as $resource)
                        <div class="resource-card rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ $resource->resource->name === 'food' ? 'utensils' : ($resource->resource->name === 'electricity' ? 'bolt' : ($resource->resource->name === 'medicine' ? 'pills' : ($resource->resource->name === 'happiness' ? 'smile' : 'tshirt'))) }} text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold mb-2 capitalize">{{ $resource->resource->name }}</h4>
                                
                                <!-- Stats -->
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Amount:</span>
                                        <span class="font-bold">{{ number_format($resource->amount) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Value:</span>
                                        <span class="font-bold">${{ number_format($resource->resource->price, 2) }}</span>
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
                                <div class="mb-4">
                                    <div class="flex justify-between text-xs text-white/70 mb-1">
                                        <span>Storage</span>
                                        <span>{{ number_format($percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                <!-- Sell Form -->
                                <form method="post" action="{{ route('client.government-resources.update', $resource) }}">
                                    @csrf
                                    @method('patch')
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-white/80 mb-1">Sell Amount</label>
                                            <input 
                                                type="number" 
                                                name="sell" 
                                                min="0" 
                                                max="{{ $resource->amount }}"
                                                value="{{ $resource->amount }}"
                                                class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-transparent"
                                                placeholder="0"
                                            >
                                        </div>
                                        <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white py-2 rounded-lg transition-colors">
                                            <i class="fas fa-coins mr-1"></i>Sell for ${{ number_format($resource->amount * $resource->resource->price, 2) }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Ungathered Resources -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-search mr-3"></i>
                    Available Resources
                </h2>
                <p class="text-gray-100 mt-1">Resources you can discover and gather</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    @foreach($ungatheredResources as $resource)
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ $resource->name === 'food' ? 'utensils' : ($resource->name === 'electricity' ? 'bolt' : ($resource->name === 'medicine' ? 'pills' : ($resource->name === 'happiness' ? 'smile' : 'tshirt'))) }} text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2 capitalize">{{ $resource->name }}</h4>
                                
                                <!-- Stats -->
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Value:</span>
                                        <span class="font-bold text-gray-800">${{ number_format($resource->price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="font-bold text-gray-500">Not Available</span>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-lock mr-1"></i>
                                        Build infrastructure to gather
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Resource Tips -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                Resource Management Tips
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-chart-line text-blue-500 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Monitor Usage</h4>
                        <p class="text-sm text-gray-600">Keep track of resource consumption to avoid shortages.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-balance-scale text-green-500 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Balance Production</h4>
                        <p class="text-sm text-gray-600">Ensure you have enough infrastructure to meet demand.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-coins text-yellow-500 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Strategic Selling</h4>
                        <p class="text-sm text-gray-600">Sell excess resources to generate income for upgrades.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
