@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">{{ $schoolClass->name }} <span class="text-lg text-gray-500 font-normal">Details & Mapping</span></h2>
        <a href="{{ route('school-classes.index') }}" class="text-blue-600 hover:underline"><i class="fas fa-arrow-left"></i> Back to Classes</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Map Subjects --}}
        <x-card title="Map Subjects">
            <form action="{{ route('school-classes.map-subjects', $schoolClass) }}" method="POST" class="x-submit">
                @csrf
                <div class="max-h-96 overflow-y-auto pr-2 border rounded-lg border-gray-200 p-4 mb-4 bg-gray-50">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach(App\Models\Subject::orderBy('name')->get() as $subject)
                            <label class="flex items-start gap-2 p-2 bg-white rounded shadow-sm border border-gray-100 cursor-pointer hover:border-blue-300 transition">
                                <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ $schoolClass->subjects->contains($subject->id) ? 'checked' : '' }}>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ $subject->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $subject->code }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Save Subjects Mapping</button>
            </form>
        </x-card>

        {{-- Assign Teachers --}}
        <x-card title="Assign Teachers">
            @php $currentSession = App\Models\AcademicSession::current()->first(); @endphp
            @if($currentSession)
                <form action="{{ route('school-classes.assign-teachers', $schoolClass) }}" method="POST" class="x-submit">
                    @csrf
                    <input type="hidden" name="academic_session_id" value="{{ $currentSession->id }}">
                    <div class="mb-4 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Assigning for current session: <strong>{{ $currentSession->name }}</strong>
                    </div>

                    <div id="teacher-assignments" class="space-y-3 mb-4">
                        @php
                            $assignedTeachers = $schoolClass->teachers()->wherePivot('academic_session_id', $currentSession->id)->get();
                        @endphp

                        @if($assignedTeachers->isEmpty())
                            <div class="assignment-row flex gap-2 items-center">
                                <div class="flex-1">
                                    <select name="assignments[0][user_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                        <option value="">Select Teacher</option>
                                        @foreach(App\Models\User::teachers()->orderBy('first_name')->get() as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <select name="assignments[0][subject_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="">All Subjects (Class Teacher)</option>
                                        @foreach($schoolClass->subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="remove-row text-red-500 hover:text-red-700 p-2"><i class="fas fa-times"></i></button>
                            </div>
                        @else
                            @foreach($assignedTeachers as $index => $assigned)
                                <div class="assignment-row flex gap-2 items-center">
                                    <div class="flex-1">
                                        <select name="assignments[{{ $index }}][user_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                            <option value="">Select Teacher</option>
                                            @foreach(App\Models\User::teachers()->orderBy('first_name')->get() as $teacher)
                                                <option value="{{ $teacher->id }}" {{ $teacher->id == $assigned->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <select name="assignments[{{ $index }}][subject_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            <option value="">All Subjects (Class Teacher)</option>
                                            @foreach($schoolClass->subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $subject->id == $assigned->pivot->subject_id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 p-2"><i class="fas fa-times"></i></button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" id="add-assignment" class="mb-4 text-sm text-blue-600 hover:underline flex items-center gap-1"><i class="fas fa-plus"></i> Add Another Teacher</button>

                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">Save Assignments</button>
                </form>

                <template id="assignment-template">
                    <div class="assignment-row flex gap-2 items-center">
                        <div class="flex-1">
                            <select name="assignments[__INDEX__][user_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                <option value="">Select Teacher</option>
                                @foreach(App\Models\User::teachers()->orderBy('first_name')->get() as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <select name="assignments[__INDEX__][subject_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="">All Subjects (Class Teacher)</option>
                                @foreach($schoolClass->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="remove-row text-red-500 hover:text-red-700 p-2"><i class="fas fa-times"></i></button>
                    </div>
                </template>
            @else
                <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg border border-yellow-200">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Please create and activate an Academic Session first.
                </div>
            @endif
        </x-card>
    </div>

    {{-- Students Enrolled --}}
    <x-card title="Students Enrolled">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Section</th>
                        <th class="px-6 py-4">Admission ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schoolClass->studentProfiles as $profile)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $profile->user->avatar ? asset($profile->user->avatar) : asset('photo_defaults.jpg') }}" class="w-8 h-8 rounded-full object-cover">
                                    {{ $profile->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $profile->classSection->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $profile->admission_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400">No students enrolled in this class.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let index = {{ isset($assignedTeachers) ? count($assignedTeachers) : 1 }};
        const template = document.getElementById('assignment-template');
        const container = document.getElementById('teacher-assignments');
        
        if(document.getElementById('add-assignment')) {
            document.getElementById('add-assignment').addEventListener('click', function() {
                if(!template) return;
                const html = template.innerHTML.replace(/__INDEX__/g, index++);
                const div = document.createElement('div');
                div.innerHTML = html;
                container.appendChild(div.firstElementChild);
            });
        }

        if(container) {
            container.addEventListener('click', function(e) {
                if(e.target.closest('.remove-row')) {
                    e.target.closest('.assignment-row').remove();
                }
            });
        }
    });
</script>
@endsection
@endsection
