<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Government Leaderboard</h1>

        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Rank</th>
                <th class="py-3 px-6 text-left">Government Name</th>
                <th class="py-3 px-6 text-center">Overall Level</th>
                {{--                <th class="py-3 px-6 text-center">Last Active</th>--}}
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            @foreach ($overallGovernments as $index => $government)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 text-left">{{ $government->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $government->overall }}</td>
                    {{--                    <td class="py-3 px-6 text-center">{{ $government->last_active->diffForHumans() }}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Economy Leaderboard</h1>

        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Rank</th>
                <th class="py-3 px-6 text-left">Government Name</th>
                <th class="py-3 px-6 text-center">Economy Level</th>
                {{--                <th class="py-3 px-6 text-center">Last Active</th>--}}
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            @foreach ($economyGovernments as $index => $government)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 text-left">{{ $government->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $government->economy }}</td>
                    {{--                    <td class="py-3 px-6 text-center">{{ $government->last_active->diffForHumans() }}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Economy Leaderboard</h1>

        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Rank</th>
                <th class="py-3 px-6 text-left">Government Name</th>
                <th class="py-3 px-6 text-center">Economy Level</th>
                {{--                <th class="py-3 px-6 text-center">Last Active</th>--}}
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            @foreach ($healthGovernments as $index => $government)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 text-left">{{ $government->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $government->health }}</td>
                    {{--                    <td class="py-3 px-6 text-center">{{ $government->last_active->diffForHumans() }}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Safety Leaderboard</h1>

        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Rank</th>
                <th class="py-3 px-6 text-left">Government Name</th>
                <th class="py-3 px-6 text-center">Safety Level</th>
                {{--                <th class="py-3 px-6 text-center">Last Active</th>--}}
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            @foreach ($safetyGovernments as $index => $government)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 text-left">{{ $government->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $government->safety }}</td>
                    {{--                    <td class="py-3 px-6 text-center">{{ $government->last_active->diffForHumans() }}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Education Leaderboard</h1>

        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Rank</th>
                <th class="py-3 px-6 text-left">Government Name</th>
                <th class="py-3 px-6 text-center">Education Level</th>
                {{--                <th class="py-3 px-6 text-center">Last Active</th>--}}
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            @foreach ($safetyGovernments as $index => $government)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 text-left">{{ $government->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $government->education }}</td>
                    {{--                    <td class="py-3 px-6 text-center">{{ $government->last_active->diffForHumans() }}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>



</x-app-layout>
