<x-app-layout>
    <x-government-overview :government="$government" />

    <!-- Infrastructure Management -->
    <div class="space-y-8">
        <!-- Current Infrastructure -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-400 to-green-500 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-building mr-3"></i>
                    Your Infrastructure
                </h2>
                <p class="text-emerald-50 mt-1">Manage your civilization's buildings and facilities</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($infrastructures as $infra)
                        <div class="infrastructure-card rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ $infra->infrastructure->type === 'HOUSING' ? 'home' : ($infra->infrastructure->type === 'FOOD' ? 'utensils' : ($infra->infrastructure->type === 'ELECTRICITY' ? 'bolt' : ($infra->infrastructure->type === 'MEDICINE' ? 'pills' : 'tshirt'))) }} text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold mb-2">{{ $infra->infrastructure->name }}</h4>
                                
                                <!-- Stats -->
                                <div class="space-y-2 text-sm mb-4">
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
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Resource:</span>
                                        <span class="font-bold capitalize">{{ $infra->infrastructure->resource_type }}</span>
                                    </div>
                                </div>

                                <!-- Population Assignment Form -->
                                <form method="post" action="{{ route('client.government-infrastructure.update', $infra) }}" class="mb-4">
                                    @method('patch')
                                    @csrf
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-white/80 mb-1">Assign Population</label>
                                            <input 
                                                type="number" 
                                                name="population" 
                                                min="0" 
                                                value="{{ $infra->population }}"
                                                class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-transparent"
                                                placeholder="0"
                                            >
                                        </div>
                                        <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white py-2 rounded-lg transition-colors">
                                            <i class="fas fa-users mr-1"></i>Update
                                        </button>
                                    </div>
                                </form>

                                <!-- Upgrade Form -->
                                <form method="post" action="{{ route('client.government-infrastructure.upgrade', $infra) }}">
                                    @csrf
                                    <div class="space-y-3">
                                        <div class="text-center">
                                            <p class="text-sm text-white/70">Upgrade to level {{ $infra->level + 1 }}</p>
                                            <p class="text-lg font-bold text-white">${{ number_format($infra->upgrade_cost) }}</p>
                                        </div>
                                        <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white py-2 rounded-lg transition-colors">
                                            <i class="fas fa-arrow-up mr-1"></i>Upgrade
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Available Infrastructure -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Available Infrastructure
                </h2>
                <p class="text-blue-100 mt-1">Build new facilities to expand your civilization</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($availableInfrastructures as $infra)
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ $infra->type === 'HOUSING' ? 'home' : ($infra->type === 'FOOD' ? 'utensils' : ($infra->type === 'ELECTRICITY' ? 'bolt' : ($infra->type === 'MEDICINE' ? 'pills' : 'tshirt'))) }} text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $infra->name }}</h4>
                                
                                <!-- Stats -->
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Cost:</span>
                                        <span class="font-bold text-gray-800">${{ number_format($infra->cost) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Produces:</span>
                                        <span class="font-bold text-gray-800 capitalize">{{ $infra->resource_type }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Base Output:</span>
                                        <span class="font-bold text-gray-800">{{ number_format($infra->base, 1) }}</span>
                                    </div>
                                </div>

                                <!-- Purchase Form -->
                                <form method="post" action="{{ route('client.government-infrastructure.store') }}">
                                    @csrf
                                    <input type="hidden" name="infrastructure" value="{{ $infra->uuid }}" />
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white py-2 rounded-lg transition-all duration-200 transform hover:scale-105">
                                        <i class="fas fa-shopping-cart mr-1"></i>Purchase
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
