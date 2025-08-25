<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="container mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-800 mb-4">Global Leaderboard</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Compete with other civilizations and see who reigns supreme across different sectors
                </p>
            </div>

            <!-- Overall Leaderboard -->
            <div class="card mb-12">
                <div class="card-header">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-trophy mr-3"></i>
                        Overall Rankings
                    </h2>
                    <p class="text-blue-100 mt-1">Top civilizations by overall score</p>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Rank</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Government</th>
                                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Overall Score</th>
                                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Population</th>
                                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Money</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overallGovernments as $index => $government)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                @if($index < 3)
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }}">
                                                        <i class="fas fa-medal"></i>
                                                    </div>
                                                @endif
                                                <span class="font-bold text-lg {{ $index < 3 ? 'text-gray-800' : 'text-gray-600' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-landmark text-white"></i>
                                                </div>
                                                <span class="font-semibold text-gray-800">{{ $government->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="text-2xl font-bold text-blue-600">{{ number_format($government->overall, 1) }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="text-gray-600">{{ number_format($government->population) }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="text-green-600 font-semibold">${{ number_format($government->money) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sector Leaderboards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Economy Leaderboard -->
                <div class="card">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Economy Rankings
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($economyGovernments->take(5) as $index => $government)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <span class="font-bold text-gray-600 mr-3">#{{ $index + 1 }}</span>
                                        <span class="font-semibold text-gray-800">{{ $government->name }}</span>
                                    </div>
                                    <span class="text-green-600 font-bold">{{ number_format($government->economy, 1) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Health Leaderboard -->
                <div class="card">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-heartbeat mr-2"></i>
                            Health Rankings
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($healthGovernments->take(5) as $index => $government)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <span class="font-bold text-gray-600 mr-3">#{{ $index + 1 }}</span>
                                        <span class="font-semibold text-gray-800">{{ $government->name }}</span>
                                    </div>
                                    <span class="text-red-600 font-bold">{{ number_format($government->health, 1) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Safety Leaderboard -->
                <div class="card">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Safety Rankings
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($safetyGovernments->take(5) as $index => $government)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <span class="font-bold text-gray-600 mr-3">#{{ $index + 1 }}</span>
                                        <span class="font-semibold text-gray-800">{{ $government->name }}</span>
                                    </div>
                                    <span class="text-blue-600 font-bold">{{ number_format($government->safety, 1) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Education Leaderboard -->
                <div class="card">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Education Rankings
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($safetyGovernments->take(5) as $index => $government)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <span class="font-bold text-gray-600 mr-3">#{{ $index + 1 }}</span>
                                        <span class="font-semibold text-gray-800">{{ $government->name }}</span>
                                    </div>
                                    <span class="text-purple-600 font-bold">{{ number_format($government->education, 1) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-12">
                <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Ready to Compete?</h3>
                    <p class="text-gray-600 mb-6">Join the competition and see if you can reach the top of the leaderboard!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn-primary">
                            <i class="fas fa-user-plus mr-2"></i>Create Account
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
