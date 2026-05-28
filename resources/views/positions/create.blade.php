<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Position — {{ $election->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">

                <form method="POST" action="{{ route('positions.store', $election) }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Position details --}}
                    <div class="mb-5">
                        <label class="block font-semibold text-gray-700 mb-1">Position Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. President"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-8">
                        <label class="block font-semibold text-gray-700 mb-1">Position Description</label>
                        <textarea name="description" rows="2" placeholder="Briefly describe the role and responsibilities..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                    {{-- Candidates section --}}
                    <div class="border-t pt-6"
                         x-data="{
                            candidates: [{ uid: 0, preview: null }],
                            nextUid: 1,
                            add() { this.candidates.push({ uid: this.nextUid++, preview: null }); },
                            remove(index) { this.candidates.splice(index, 1); },
                            setPreview(index, e) {
                                if (e.target.files[0]) {
                                    this.candidates[index].preview = URL.createObjectURL(e.target.files[0]);
                                }
                            }
                         }">

                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-bold text-gray-800">Candidates</h3>
                            <button type="button" @click="add()"
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold px-4 py-2 rounded-lg">
                                + Add Another Candidate
                            </button>
                        </div>

                        <div class="space-y-6">
                            <template x-for="(candidate, index) in candidates" :key="candidate.uid">
                                <div class="border border-gray-200 rounded-xl p-5 bg-gray-50 relative">

                                    {{-- Remove button (only when more than 1) --}}
                                    <button type="button" x-show="candidates.length > 1"
                                            @click="remove(index)"
                                            class="absolute top-3 right-3 text-red-400 hover:text-red-600 text-xs font-semibold">
                                        ✕ Remove
                                    </button>

                                    <div class="flex gap-5 items-start">

                                        {{-- Photo upload --}}
                                        <div class="flex flex-col items-center flex-shrink-0">
                                            <div class="w-20 h-20 rounded-full border-2 border-blue-200 bg-blue-50 overflow-hidden flex items-center justify-center mb-2">
                                                <img x-show="candidate.preview" :src="candidate.preview"
                                                     class="w-full h-full object-cover">
                                                <span x-show="!candidate.preview" class="text-3xl text-blue-300">👤</span>
                                            </div>
                                            <label class="cursor-pointer text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                Upload Photo
                                                <input type="file" :name="`candidates[${index}][photo]`"
                                                       accept="image/*" class="hidden"
                                                       @change="setPreview(index, $event)">
                                            </label>
                                        </div>

                                        {{-- Candidate fields --}}
                                        <div class="flex-1 space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Full Name <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" :name="`candidates[${index}][name]`"
                                                       placeholder="Candidate full name"
                                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                       required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Short Bio</label>
                                                <textarea :name="`candidates[${index}][description]`" rows="2"
                                                          placeholder="Brief introduction..."
                                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                          maxlength="500"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>

                    <div class="flex items-center gap-4 mt-8">
                        <button type="submit"
                            style="background:#2563eb; color:white; padding:12px 28px; border-radius:8px; font-weight:600;">
                            Save Position &amp; Candidates
                        </button>
                        <a href="{{ route('positional-elections.index') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
