<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Registered Users
                </h1>

                <p class="text-gray-500 mt-2">
                    View all users who have created accounts on the platform.
                </p>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($users as $user)

                    <div class="border-b py-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-bold">
                                {{ $user->name }}
                            </h2>

                            <p class="text-gray-500 text-sm">
                                {{ $user->email }}
                            </p>
                        </div>

                        <span style="background:#2563eb; color:white; padding:6px 12px; border-radius:999px; font-size:12px;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                @empty

                    <p class="text-gray-500">
                        No registered users yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>