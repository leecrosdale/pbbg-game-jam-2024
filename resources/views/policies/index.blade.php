<x-app-layout>
    <x-government-overview :government="$government" />

    <!-- Policy Management -->
    <div class="space-y-8">
        <!-- Available Policies -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-cog mr-3"></i>
                    Available Policies
                </h2>
                <p class="text-purple-100 mt-1">Enact policies to improve your civilization</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($availablePolicies as $type => $policy)
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 card-hover">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r {{ $policy['color'] }} rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="{{ $policy['icon'] }} text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $policy['name'] }}</h4>
                                <p class="text-sm text-gray-600 mb-4">{{ $policy['description'] }}</p>
                                
                                <!-- Effects -->
                                <div class="space-y-1 mb-4">
                                    @foreach($policy['effects'] as $effect)
                                        <div class="text-xs text-gray-700 bg-white/50 rounded px-2 py-1">
                                            {{ $effect }}
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Cost and Enact Button -->
                                <div class="space-y-3">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Cost</p>
                                        <p class="text-xl font-bold text-gray-800">${{ number_format($policy['cost']) }}</p>
                                    </div>
                                    
                                    @if($government->money >= $policy['cost'])
                                        <form method="POST" action="{{ route('policies.enact') }}">
                                            @csrf
                                            <input type="hidden" name="policy_type" value="{{ $type }}">
                                            <button type="submit" class="w-full bg-gradient-to-r {{ $policy['color'] }} hover:opacity-90 text-white py-2 rounded-lg transition-all duration-200 transform hover:scale-105">
                                                <i class="fas fa-play mr-1"></i>Enact Policy
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg cursor-not-allowed">
                                            <i class="fas fa-lock mr-1"></i>Insufficient Funds
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Active Policies -->
        @if($activePolicies->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        Active Policies
                    </h2>
                    <p class="text-green-100 mt-1">Currently active policies and their effects</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($activePolicies as $policy)
                            <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-xl p-6">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-check text-white text-2xl"></i>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ ucwords(str_replace('_', ' ', $policy->type)) }}</h4>
                                    <p class="text-sm text-gray-600 mb-4">{{ $policy->getDescription() }}</p>
                                    
                                    <!-- Policy Details -->
                                    <div class="space-y-2 text-sm mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Cost:</span>
                                            <span class="font-bold text-gray-800">${{ number_format($policy->cost) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Duration:</span>
                                            <span class="font-bold text-gray-800">{{ $policy->duration }} turns</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Enacted:</span>
                                            <span class="font-bold text-gray-800">{{ $policy->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Cancel Button -->
                                    <form method="POST" action="{{ route('policies.cancel', $policy) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition-colors">
                                            <i class="fas fa-times mr-1"></i>Cancel Policy
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Policy Tips -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                Policy Management Tips
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-coins text-green-500 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Strategic Investment</h4>
                        <p class="text-sm text-gray-600">Choose policies that align with your current needs and available funds.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-clock text-blue-500 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Timing Matters</h4>
                        <p class="text-sm text-gray-600">Policies last for 10 turns, so plan their implementation carefully.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-balance-scale text-purple-500 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-gray-800">Balance Effects</h4>
                        <p class="text-sm text-gray-600">Consider both positive effects and costs when choosing policies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
