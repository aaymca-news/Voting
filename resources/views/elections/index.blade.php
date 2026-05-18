<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">
                    Elections
                </h1>

                <a href="{{ route('elections.create') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow">
                    Create Election
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($elections as $election)

                    <div class="border-b last:border-b-0 py-8">

                        <div class="flex justify-between items-start gap-6">

                            {{-- LEFT --}}
                            <div>

                                <h2 class="text-3xl font-bold text-gray-900">
                                    {{ $election->title }}
                                </h2>

                                <p class="text-gray-500 text-lg mt-2">
                                    {{ $election->description }}
                                </p>

                                <div class="mt-6 flex flex-wrap gap-3">

                                    <a href="{{ route('voting-items.create', $election) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-lg">
                                        Add Motion / Agenda
                                    </a>

                                    <a href="{{ route('votes.results', $election) }}"
                                       class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl text-lg">
                                        View Results
                                    </a>

                                </div>

                            </div>

                            {{-- RIGHT --}}
                            <div class="flex items-center gap-3">

                                <button type="button"
                                        onclick="document.getElementById('delete-election-box-{{ $election->id }}').style.display='flex'"
                                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-lg">
                                    Delete
                                </button>

                            </div>

                        </div>

                        {{-- DELETE POPUP --}}
                        <div id="delete-election-box-{{ $election->id }}"
                             style="display:none;"
                             class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Delete Election
                                </h2>

                                <p class="text-gray-600 mb-6">
                                    Are you sure you want to delete this election?
                                    This action will permanently remove:
                                </p>

                                <ul class="list-disc ml-5 text-gray-600 mb-6 space-y-1">
                                    <li>The election</li>
                                    <li>All motions/agendas</li>
                                    <li>All voting records</li>
                                    <li>All election options</li>
                                </ul>

                                <div class="flex justify-end gap-3">

                                    <button type="button"
                                            onclick="document.getElementById('delete-election-box-{{ $election->id }}').style.display='none'"
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                        Cancel
                                    </button>

                                    <form method="POST"
                                          action="{{ route('elections.destroy', $election) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                            Delete Election
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <p class="text-gray-500 text-lg">
                        No elections created yet.
                    </p>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>