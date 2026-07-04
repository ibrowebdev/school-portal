<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Register Class Subjects</h3>
            <p class="text-gray-500 mt-1">Assign subjects to classes for a specific term and session.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <form wire:submit.prevent="saveRegistration" class="space-y-6">
            <!-- Filter Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Academic Session -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Session <span class="text-red-500">*</span></label>
                    <select wire:model.live="selectedSessionId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Select Session --</option>
                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedSessionId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Term -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Term <span class="text-red-500">*</span></label>
                    <select wire:model.live="selectedTermId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Select Term --</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}">{{ $term->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedTermId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">School Class <span class="text-red-500">*</span></label>
                    <select wire:model.live="selectedClassId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedClassId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Subjects Selection -->
            @if($selectedSessionId && $selectedTermId && $selectedClassId)
                <div class="border-t border-gray-100 pt-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-lg font-medium text-gray-800">Select Subjects</h4>
                        <span class="text-sm text-gray-500">{{ count($subjects) }} available subjects</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($subjects as $subject)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="checkbox" wire:model="selectedSubjectIds" value="{{ $subject->id }}" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">{{ $subject->name }} @if($subject->code) ({{ $subject->code }}) @endif</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedSubjectIds') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="border-t border-gray-100 pt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Save Registration
                    </button>
                </div>
            @elseif($selectedClassId)
                <div class="border-t border-gray-100 pt-6 text-center text-gray-500 py-8">
                    <i class="fas fa-info-circle text-gray-400 text-3xl mb-3"></i>
                    <p>Please select an Academic Session and Term to view subjects.</p>
                </div>
            @endif
        </form>
    </div>
</div>
