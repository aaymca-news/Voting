<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Elections
                </h1>

                <a href="{{ route('elections.create') }}"
                   style="display:inline-block; background:#16a34a; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
                    Create Election
                </a>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($elections as $election)

                    <div class="border-b pb-6 mb-6">

                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $election->title }}
                        </h2>

                        <p class="text-gray-500 mt-2">
                            {{ $election->description }}
                        </p>

                        <div style="margin-top:16px; display:flex; gap:12px; flex-wrap:wrap;">

                            <a href="{{ route('voting-items.create', $election) }}"
   style="display:inline-block; background:#2563eb; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
    Add Motion / Agenda
</a>

            

                            <a href="{{ route('votes.results', $election) }}"
                               style="display:inline-block; background:#9333ea; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
                                View Results
                            </a>

                        </div>

                    </div>

                @empty

                    <p class="text-gray-500">
                        No elections created yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>