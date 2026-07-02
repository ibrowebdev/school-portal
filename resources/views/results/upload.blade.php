@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Upload Results" parent="Results" :parentRoute="route('results.index')" />

    {{-- Filter Form to load spreadsheet --}}
    <x-card noPadding="true" class="mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-search mr-2"></i> Select Class & Subject</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('results.upload') }}" method="GET" id="loadForm">
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
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Load Student List
                    </button>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Spreadsheet Form --}}
    @if(request()->has('school_class_id'))
        <x-card noPadding="true">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800">Enter Scores</h3>
                <div class="text-sm text-gray-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                    For <strong>{{ $subjects->firstWhere('id', request('subject_id'))?->name ?? 'Subject' }}</strong> 
                    in <strong>{{ $classes->firstWhere('id', request('school_class_id'))?->name ?? 'Class' }}</strong>
                </div>
            </div>

            @if($students->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <i class="fas fa-users-slash text-4xl mb-3 block"></i>
                    No students enrolled in this class.
                </div>
            @else
                <form action="{{ route('results.store') }}" method="POST" class="x-submit" data-redirect="true">
                    @csrf
                    <input type="hidden" name="academic_session_id" value="{{ request('academic_session_id') }}">
                    <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                    <input type="hidden" name="school_class_id" value="{{ request('school_class_id') }}">
                    <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">#</th>
                                    <th class="px-6 py-4">Student Name</th>
                                    <th class="px-6 py-4 w-48 text-center">CA Score (Max 40)</th>
                                    <th class="px-6 py-4 w-48 text-center">Exam Score (Max 60)</th>
                                    <th class="px-6 py-4 w-32 text-center">Total (100)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($students as $index => $student)
                                    @php
                                        $result = $existingResults->get($student->id);
                                        $ca = $result ? $result->ca_score : 0;
                                        $exam = $result ? $result->exam_score : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-800">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $student->avatar ? asset($student->avatar) : asset('photo_defaults.jpg') }}" class="w-8 h-8 rounded-full object-cover">
                                                {{ $student->name }}
                                            </div>
                                            <input type="hidden" name="results[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="results[{{ $index }}][ca_score]" value="{{ $ca }}" min="0" max="40" step="0.01" class="score-input ca-input w-full px-3 py-2 border border-gray-300 rounded-md text-center focus:ring-blue-500 focus:border-blue-500" required>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="results[{{ $index }}][exam_score]" value="{{ $exam }}" min="0" max="60" step="0.01" class="score-input exam-input w-full px-3 py-2 border border-gray-300 rounded-md text-center focus:ring-purple-500 focus:border-purple-500" required>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-gray-800 total-display">
                                            {{ $ca + $exam }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold shadow-sm flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Results
                        </button>
                    </div>
                </form>
            @endif
        </x-card>
    @endif
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('school_class_id');
        const subjectSelect = document.getElementById('subject_id');

        if(classSelect) {
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
        }
        
        const sessionSelect = document.getElementById('academic_session_id');
        const termSelect = document.getElementById('term_id');

        if(sessionSelect) {
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
        }

        // Live Total Calculation
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const caInput = row.querySelector('.ca-input');
            const examInput = row.querySelector('.exam-input');
            const totalDisplay = row.querySelector('.total-display');

            if(caInput && examInput) {
                const calcTotal = () => {
                    const ca = parseFloat(caInput.value) || 0;
                    const exam = parseFloat(examInput.value) || 0;
                    totalDisplay.textContent = (ca + exam).toFixed(2).replace(/\.00$/, '');
                };
                caInput.addEventListener('input', calcTotal);
                examInput.addEventListener('input', calcTotal);
            }
        });
    });
</script>
@endsection
@endsection
