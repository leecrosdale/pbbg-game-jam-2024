<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revolve - Shape the Future of Your World</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">

<!-- Navbar -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <a href="/" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-800">Revolve</span>
            </a>
            <div class="hidden md:flex space-x-8">
                <a href="#features" class="text-gray-600 hover:text-blue-500 transition-colors">Features</a>
                <a href="#about" class="text-gray-600 hover:text-blue-500 transition-colors">About</a>
                <a href="{{ route('leaderboard.index') }}" class="text-gray-600 hover:text-blue-500 transition-colors">Leaderboard</a>
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-500 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all">Join Now</a>
            </div>
            <div class="md:hidden">
                <button class="text-gray-600 hover:text-blue-500">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-bg text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="animate-float mb-8">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-globe text-4xl"></i>
                </div>
            </div>
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Shape the Future of 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Your World</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                Build a civilization, manage resources, and navigate the cycles of time in this dynamic strategy game.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105">
                    <i class="fas fa-rocket mr-2"></i>Start Your Journey
                </a>
                <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg text-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                    <i class="fas fa-play mr-2"></i>Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Game Features</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Experience a rich, dynamic world where every decision shapes your civilization's destiny.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-xl card-hover">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Resource Management</h3>
                <p class="text-blue-100">Carefully manage food, electricity, medicine, and more as your population grows and thrives.</p>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-xl card-hover">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-leaf text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Dynamic Seasons</h3>
                <p class="text-green-100">Seasons change every 8 turns, affecting your society's growth and resource consumption.</p>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-xl card-hover">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-landmark text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Government System</h3>
                <p class="text-purple-100">Create your government, enact policies, and make decisions that impact the world.</p>
            </div>
            
            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-8 rounded-xl card-hover">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Population Growth</h3>
                <p class="text-red-100">Manage your population and balance resources to ensure stability and growth.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8">About Revolve</h2>
            <p class="text-xl text-gray-600 mb-12 leading-relaxed">
                Revolve is a dynamic, strategy-based civilization management game where you guide your society through changing seasons, 
                resource challenges, and strategic decision-making. Shape your government's policies, watch your population grow, 
                and navigate the complex challenges of a simulated world.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Real-time Strategy</h3>
                    <p class="text-gray-600">Make decisions that impact your civilization in real-time.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Deep Analytics</h3>
                    <p class="text-gray-600">Track your progress with detailed statistics and insights.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Competitive</h3>
                    <p class="text-gray-600">Compete with other players on the global leaderboard.</p>
                </div>
            </div>
            
            <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-12 py-4 rounded-lg text-2xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105 inline-block">
                <i class="fas fa-rocket mr-2"></i>Join the Revolution
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="gradient-bg text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Build Your Empire?</h2>
        <p class="text-xl text-blue-100 mb-12 max-w-2xl mx-auto">
            Start your journey today, build your civilization, and lead your people to greatness!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </a>
            <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg text-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-globe text-white"></i>
                    </div>
                    <span class="text-xl font-bold">Revolve</span>
                </div>
                <p class="text-gray-400">Shape the future of your world in this dynamic civilization management game.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Game</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                    <li><a href="{{ route('leaderboard.index') }}" class="hover:text-white transition-colors">Leaderboard</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Tutorial</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Account</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Profile</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Revolve Game. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
