@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="View Results" parent="Results" :parentRoute="route('results.index')" />

    {{-- Filter Form --}}
    <x-card noPadding="true" class="mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-filter mr-2"></i> Filter Results</h3>
            @can('upload-results')
            <a href="{{ route('results.upload') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium text-sm">
                <i class="fas fa-upload mr-1"></i> Upload Results
            </a>
            @endcan
        </div>
        <div class="p-6">
            <form action="{{ route('results.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Academic Session</label>
                        <select name="academic_session_id" id="academic_session_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ request('academic_session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                        <select name="term_id" id="term_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                        <select name="school_class_id" id="school_class_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select name="subject_id" id="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-medium">
                        Fetch Results
                    </button>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Results Table --}}
    @if(request()->has('school_class_id'))
        <x-card noPadding="true">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800">Result Sheet</h3>
                <div class="text-sm text-gray-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                    Showing results for <strong>{{ $subjects->firstWhere('id', request('subject_id'))?->name ?? 'Subject' }}</strong> 
                    in <strong>{{ $classes->firstWhere('id', request('school_class_id'))?->name ?? 'Class' }}</strong>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4 text-center">CA Score (40)</th>
                            <th class="px-6 py-4 text-center">Exam Score (60)</th>
                            <th class="px-6 py-4 text-center">Total (100)</th>
                            <th class="px-6 py-4 text-center">Grade</th>
                            <th class="px-6 py-4">Remark</th>
                            <th class="px-6 py-4 text-right">Report Card</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $key => $result)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-500">{{ $key + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $result->student->avatar ? asset($result->student->avatar) : asset('photo_defaults.jpg') }}" class="w-8 h-8 rounded-full object-cover">
                                        {{ $result->student->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-blue-600">{{ $result->ca_score }}</td>
                                <td class="px-6 py-4 text-center font-medium text-purple-600">{{ $result->exam_score }}</td>
                                <td class="px-6 py-4 text-center font-bold text-gray-800">{{ $result->total_score }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                        @if($result->grade == 'A') bg-green-100 text-green-800
                                        @elseif(in_array($result->grade, ['B', 'C'])) bg-blue-100 text-blue-800
                                        @elseif($result->grade == 'D') bg-yellow-100 text-yellow-800
                                        @elseif(in_array($result->grade, ['E', 'F'])) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ $result->grade ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $result->remark ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('results.report', ['student' => $result->student_id, 'academic_session_id' => $result->academic_session_id, 'term_id' => $result->term_id]) }}" class="text-blue-600 hover:underline text-sm font-medium">
                                        View Report <i class="fas fa-chevron-right text-xs ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-clipboard-list text-4xl mb-3 block"></i>
                                    No results found for the selected criteria.
                                    @can('upload-results')
                                        <div class="mt-4">
                                            <a href="{{ route('results.upload', request()->all()) }}" class="text-blue-600 hover:underline font-medium">Click here to upload results</a>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('school_class_id');
        const subjectSelect = document.getElementById('subject_id');

        classSelect.addEventListener('change', function() {
            const classId = this.value;
            if(!classId) {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                return;
            }

            fetch(`/api/class-subjects/${classId}`)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">Select Subject</option>';
                    data.forEach(sub => {
                        html += `<option value="${sub.id}">${sub.name}</option>`;
                    });
                    subjectSelect.innerHTML = html;
                });
        });
        
        const sessionSelect = document.getElementById('academic_session_id');
        const termSelect = document.getElementById('term_id');

        sessionSelect.addEventListener('change', function() {
            const sessionId = this.value;
            if(!sessionId) {
                termSelect.innerHTML = '<option value="">Select Term</option>';
                return;
            }

            fetch(`/api/session-terms/${sessionId}`)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">Select Term</option>';
                    data.forEach(t => {
                        html += `<option value="${t.id}">${t.name}</option>`;
                    });
                    termSelect.innerHTML = html;
                });
        });
    });
</script>
@endsection
@endsection
