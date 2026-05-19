<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Groups
                    </h1>

                    <p class="text-gray-500 mt-2">
                        Manage voting groups.
                    </p>
                </div>

                <a href="{{ route('groups.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
                    Create Group
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-2xl p-6">

                @forelse($groups as $group)

                    <div class="border-b last:border-b-0 py-6">

                        <div class="flex justify-between items-center">

                            {{-- LEFT SIDE --}}
                            <div>
                                <a href="{{ route('groups.show', $group) }}"
                                   class="text-2xl font-bold text-blue-600 hover:text-blue-800">
                                    {{ $group->name }}
                                </a>

                                <p class="text-gray-500 mt-2">
                                    {{ $group->description }}
                                </p>

                                <p class="text-gray-400 text-sm mt-3">
                                    Code: {{ $group->code }}
                                </p>
                            </div>

                            {{-- RIGHT SIDE --}}
                            <div class="flex items-center gap-3">

                                <a href="{{ route('groups.show', $group) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Open
                                </a>

                                {{-- EDIT BUTTON --}}
                                <button type="button"
                                        onclick="document.getElementById('edit-group-box-{{ $group->id }}').style.display='flex'"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                                    Edit
                                </button>

                                {{-- DELETE BUTTON --}}
                                <button type="button"
                                        onclick="document.getElementById('delete-group-box-{{ $group->id }}').style.display='flex'"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    Delete
                                </button>

                            </div>

                        </div>

                        {{-- EDIT POPUP --}}
                        <div id="edit-group-box-{{ $group->id }}"
                             style="display:none;"
                             class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

                                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                                    Edit Group
                                </h2>

                                <form method="POST"
                                      action="{{ route('groups.update', $group) }}">

                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Group Name
                                        </label>
                                        <input type="text"
                                               name="name"
                                               value="{{ $group->name }}"
                                               required
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Description
                                        </label>
                                        <textarea name="description"
                                                  rows="3"
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $group->description }}</textarea>
                                    </div>

                                    <div class="flex justify-end gap-3">

                                        <button type="button"
                                                onclick="document.getElementById('edit-group-box-{{ $group->id }}').style.display='none'"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                            Cancel
                                        </button>

                                        <button type="submit"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                                            Save Changes
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                        {{-- DELETE POPUP --}}
                        <div id="delete-group-box-{{ $group->id }}"
                             style="display:none;"
                             class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">

                            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Delete Group
                                </h2>

                                <p class="text-gray-600 mb-6">
                                    Are you sure you want to delete this group?
                                    This will permanently remove:
                                </p>

                                <ul class="list-disc ml-5 text-gray-600 mb-6 space-y-1">
                                    <li>The group</li>
                                    <li>All group memberships</li>
                                    <li>All elections under the group</li>
                                    <li>All motions and voting records</li>
                                </ul>

                                <div class="flex justify-end gap-3">

                                    <button type="button"
                                            onclick="document.getElementById('delete-group-box-{{ $group->id }}').style.display='none'"
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                        Cancel
                                    </button>

                                    <form method="POST"
                                          action="{{ route('groups.destroy', $group) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                            Delete Group
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

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