<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Groups
                    </h1>

                    <p class="text-gray-500 mt-2">
                        Manage voting groups.
                    </p>
                </div>

                <a href="{{ route('groups.create') }}"
                   style="display:inline-block; background:#2563eb; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
                    Create Group
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($groups as $group)

                    <div class="border-b py-4">

                        <a href="{{ route('groups.show', $group) }}"
                           class="text-xl font-semibold text-blue-600 hover:underline">
                            {{ $group->name }}
                        </a>

                        <p class="text-gray-500 mt-1">
                            {{ $group->description }}
                        </p>

                        <p class="text-gray-400 text-sm mt-2">
                            Code: {{ $group->code }}
                        </p>

                    </div>

                @empty

                    <p class="text-gray-500">
                        No groups created yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>