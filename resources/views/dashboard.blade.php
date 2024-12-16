<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Full Page Background -->
    <div class="py-12 min-h-screen bg-gray-100">

        <!-- Centered Container -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dynamic Greeting -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Greeting with random colors per letter -->
                    <h1 class="text-4xl font-bold text-center">
                        @foreach (str_split("Hello!... " . auth()->user()->name) as $letter)
                            <span style="color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};">{{ $letter }}</span>
                        @endforeach
                    </h1>
                </div>
            </div>

            <!-- User Grid Section -->
            <div class="mt-6 bg-gray-700 bg-opacity-50 rounded-lg shadow-sm"> <!-- Transparent gray background -->
                <div class="p-6 text-gray-900">
                    <!-- Users Grid: 3 columns per row -->
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-4">
                        @foreach ($users as $user)
                            <a href="{{ route('chat', $user->id) }}"
                               class="bg-gray-100 p-4 rounded-lg shadow-md block hover:bg-sky-500 transition duration-300">
                                <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                                <p>{{ $user->email }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-end">
        <div class="pagination-links">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
