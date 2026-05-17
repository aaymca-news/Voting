<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-2xl p-6">

                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Edit Motion / Agenda
                </h1>

                <p class="text-gray-500 mb-6">
                    Update the motion/agendum before voting starts.
                </p>

                <form method="POST"
                      action="{{ route('voting-items.update', $votingItem) }}">

                    @csrf
                    @method('PATCH')

                    <div class="mb-6">

                        <label class="block font-medium text-sm text-gray-700 mb-2">
                            Motion / Agenda
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title', $votingItem->title) }}"
                               class="w-full border rounded-lg px-4 py-3"
                               required>

                    </div>

                    <div class="mb-6">

                        <label class="block font-medium text-sm text-gray-700 mb-2">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="w-full border rounded-lg px-4 py-3">{{ old('description', $votingItem->description) }}</textarea>

                    </div>

                    <div class="flex items-center gap-4">

                        <button type="submit"
                            style="background:#16a34a; color:white; padding:10px 18px; border:none; border-radius:8px; cursor:pointer;">
                            Update Motion / Agenda
                        </button>

                        <a href="{{ route('groups.show', $votingItem->election->group_id) }}"
                           style="color:#555; text-decoration:none;">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>