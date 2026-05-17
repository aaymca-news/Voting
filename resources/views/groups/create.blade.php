<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Group</h1>

            <div class="bg-white shadow rounded-2xl p-6">
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Group Name</label>
                        <input type="text"
                               name="name"
                               class="w-full border-gray-300 rounded-lg"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Description</label>
                        <textarea name="description"
                                  class="w-full border-gray-300 rounded-lg"
                                  rows="4"></textarea>
                    </div>

                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Save Group
                    </button>

                    <a href="{{ route('groups.index') }}"
                       class="ml-3 text-gray-600 hover:underline">
                        Cancel
                    </a>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>