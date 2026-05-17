<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-2xl p-6">

                <h1 class="text-3xl font-bold mb-6">
                    Add Candidate / Option
                </h1>

                <p class="mb-6 text-gray-500">
                    Election:
                    <strong>{{ $election->title }}</strong>
                </p>

                <form method="POST"
                      action="{{ route('election-options.store', $election) }}">

                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">
                            Candidate / Option Name
                        </label>

                        <input type="text"
                               name="name"
                               class="w-full border-gray-300 rounded-lg shadow-sm"
                               required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                    </div>

                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">
                        Save Candidate
                    </button>

                    <div class="mt-6">
    <button type="submit"
            style="display:inline-block; background:#16a34a; color:white; padding:10px 16px; border-radius:8px; text-decoration:none;">
        Save Candidate
    </button>

    <a href="{{ route('elections.index') }}"
       style="margin-left:12px; color:#555; text-decoration:none;">
        Cancel
    </a>
</div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>