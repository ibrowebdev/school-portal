@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Attendance Report" parent="Attendance" :parentRoute="route('attendance.index')" />

    {{-- Filter Form --}}
    <x-card noPadding="true" class="mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-chart-bar mr-2"></i> Report Filter</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('attendance.report') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Academic Session</label>
                        <select name="academic_session_id" id="academic_session_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ request('academic_session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Term <span class="text-red-500">*</span></label>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-500">*</span></label>
                        <select name="school_class_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-medium">
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Report Grid --}}
    @if(request()->has('school_class_id'))
        <x-card noPadding="true">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800">
                    Termly Report: {{ $classes->firstWhere('id', request('school_class_id'))?->name }}
                </h3>
            </div>

            @if($students->isEmpty() || $dates->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                    No attendance records found for the selected criteria.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-800 text-white font-medium">
                            <tr>
                                <th class="px-4 py-3 border border-gray-700 w-48 sticky left-0 bg-gray-800 z-10">Student Name</th>
                                <th class="px-2 py-3 border border-gray-700 text-center">%</th>
                                @foreach($dates as $date)
                                    <th class="px-2 py-3 border border-gray-700 text-center text-xs w-8 transform -rotate-45 h-20 whitespace-nowrap align-bottom">
                                        <div class="w-4 ml-3">{{ \Carbon\Carbon::parse($date)->format('d M') }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                @php
                                    $studentRecords = $attendanceData->get($student->id, collect());
                                    $presentCount = $studentRecords->where('status', 'present')->count();
                                    $totalDays = $dates->count();
                                    $percentage = $totalDays > 0 ? round(($presentCount / $totalDays) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border border-gray-200 font-medium text-gray-800 sticky left-0 bg-white">
                                        {{ $student->name }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-200 text-center font-bold {{ $percentage >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $percentage }}%
                                    </td>
                                    @foreach($dates as $date)
                                        @php
                                            $record = $studentRecords->firstWhere('date', $date);
                                            $status = $record ? $record->status : null;
                                        @endphp
                                        <td class="px-2 py-2 border border-gray-200 text-center text-lg font-bold">
                                            @if($status == 'present')
                                                <span class="text-green-500"><i class="fas fa-check"></i></span>
                                            @elseif($status == 'absent')
                                                <span class="text-red-500"><i class="fas fa-times"></i></span>
                                            @elseif($status == 'late')
                                                <span class="text-yellow-500">L</span>
                                            @elseif($status == 'excused')
                                                <span class="text-blue-500">E</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-white border-t border-gray-100">
                    <h4 class="font-bold text-sm text-gray-700 mb-2">Legend</h4>
                    <div class="flex gap-6 text-sm">
                        <div class="flex items-center gap-2"><span class="text-green-500 font-bold text-lg"><i class="fas fa-check"></i></span> Present</div>
                        <div class="flex items-center gap-2"><span class="text-red-500 font-bold text-lg"><i class="fas fa-times"></i></span> Absent</div>
                        <div class="flex items-center gap-2"><span class="text-yellow-500 font-bold text-lg">L</span> Late</div>
                        <div class="flex items-center gap-2"><span class="text-blue-500 font-bold text-lg">E</span> Excused</div>
                    </div>
                </div>
            @endif
        </x-card>
    @endif
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endsection
@endsection
