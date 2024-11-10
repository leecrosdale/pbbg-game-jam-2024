<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revolve - Shape the Future of Your World</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">

<!-- Navbar -->
<nav class="bg-gray-800 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/" class="text-3xl font-bold text-teal-400 hover:text-teal-300">Revolve</a>
        <ul class="flex space-x-6 text-lg">
            <li><a href="#features" class="hover:text-teal-400">Features</a></li>
            <li><a href="#about" class="hover:text-teal-400">About</a></li>
            <li><a href="#join" class="hover:text-teal-400">Join</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-teal-400">Login</a></li>
            <li><a href="{{ route('leaderboard.index') }}" class="hover:text-teal-400">Leaderboards</a></li>
        </ul>
    </div>
</nav>

<!-- Hero Section -->
<section class="bg-cover bg-center text-center py-40" style="background-image: url('images/logo.png');">
    <div class="bg-black bg-opacity-50 py-20">
        <h1 class="text-5xl font-extrabold text-white leading-tight mb-6">Revolve: Shape the Future of Your World</h1>
        <p class="text-xl text-teal-300 mb-6">Build a civilization, manage resources, and navigate the cycles of time.</p>
        <a href="#join" class="bg-teal-500 text-white px-6 py-3 rounded-full text-xl font-semibold hover:bg-teal-400 transition">Start Your Journey</a>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 bg-gray-100">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-semibold mb-12 text-gray-800">Game Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-teal-500 mb-4">Resource Management</h3>
                <p class="text-gray-700">Carefully manage food, electricity, medicine, and more as your population grows and thrives.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-teal-500 mb-4">Dynamic Seasons</h3>
                <p class="text-gray-700">Seasons change every 8 turns, affecting your society's growth and resource consumption.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-teal-500 mb-4">Government System</h3>
                <p class="text-gray-700">Create your government, enact policies, and make decisions that impact the world.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-teal-500 mb-4">Population Growth</h3>
                <p class="text-gray-700">Manage your population and balance resources to ensure stability and growth.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-24">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-semibold mb-12 text-white">About Revolve</h2>
        <p class="text-xl text-gray-400 mb-8">Revolve is a dynamic, text-based game where you manage a civilization through changing seasons, resource management, and strategic decision-making. Shape your government's policies, watch your population grow, and navigate the complex challenges of a simulated world.</p>
        <a href="/register" class="bg-teal-500 text-white px-8 py-4 rounded-full text-2xl font-semibold hover:bg-teal-400 transition">Join the Revolution</a>
    </div>
</section>

<!-- Join Section -->
<section id="join" class="bg-gray-800 text-center py-24">
    <div class="container mx-auto">
        <h2 class="text-4xl font-semibold mb-6 text-white">Ready to Build Your Empire?</h2>
        <p class="text-lg text-gray-300 mb-12">Start your journey today, build your civilization, and lead your people to greatness!</p>
        <a href="/register" class="bg-teal-500 text-white px-8 py-4 rounded-full text-2xl font-semibold hover:bg-teal-400 transition">Create Account</a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 py-12">
    <div class="container mx-auto text-center text-gray-400">
        <p>&copy; 2024 Revolve Game. All rights reserved.</p>
        <div class="mt-4">
            <a href="#" class="hover:text-teal-400">Privacy Policy</a> |
            <a href="#" class="hover:text-teal-400">Terms of Service</a>
        </div>
    </div>
</footer>

</body>
</html>
