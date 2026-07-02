@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Mark Attendance" parent="Attendance" :parentRoute="route('attendance.index')" />

    {{-- Filter Form --}}
    <x-card noPadding="true" class="mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-calendar-check mr-2"></i> Select Class & Date</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('attendance.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Load Students
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Attendance Form --}}
    @if(request()->has('school_class_id'))
        @if(!$currentSession || !$currentTerm)
            <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg border border-yellow-200">
                <i class="fas fa-exclamation-triangle mr-2"></i> No active Academic Session or Term found. Please set one to mark attendance.
            </div>
        @else
            <x-card noPadding="true">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">
                        Attendance for {{ $classes->firstWhere('id', request('school_class_id'))?->name }} on {{ \Carbon\Carbon::parse($selectedDate)->format('D, M d, Y') }}
                    </h3>
                    
                    <div class="flex gap-2">
                        <button type="button" class="mark-all px-3 py-1 bg-green-100 text-green-700 rounded border border-green-200 text-xs font-medium hover:bg-green-200" data-status="present">All Present</button>
                        <button type="button" class="mark-all px-3 py-1 bg-red-100 text-red-700 rounded border border-red-200 text-xs font-medium hover:bg-red-200" data-status="absent">All Absent</button>
                    </div>
                </div>

                @if($students->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        <i class="fas fa-users-slash text-4xl mb-3 block"></i>
                        No students enrolled in this class.
                    </div>
                @else
                    <form action="{{ route('attendance.store') }}" method="POST" class="x-submit" data-then="reload">
                        @csrf
                        <input type="hidden" name="school_class_id" value="{{ request('school_class_id') }}">
                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                        <input type="hidden" name="academic_session_id" value="{{ $currentSession->id }}">
                        <input type="hidden" name="term_id" value="{{ $currentTerm->id }}">

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 w-12">#</th>
                                        <th class="px-6 py-4">Student Name</th>
                                        <th class="px-6 py-4 w-64 text-center">Status</th>
                                        <th class="px-6 py-4">Remark (Optional)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($students as $index => $student)
                                        @php
                                            $status = $attendanceRecords->get($student->id) ?? 'present';
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-800">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $student->avatar ? asset($student->avatar) : asset('photo_defaults.jpg') }}" class="w-8 h-8 rounded-full object-cover">
                                                    {{ $student->name }}
                                                </div>
                                                <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex gap-4 justify-center">
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $index }}][status]" value="present" class="status-radio text-green-600 focus:ring-green-500" {{ $status == 'present' ? 'checked' : '' }}>
                                                        <span class="text-green-700 font-medium text-xs uppercase">Present</span>
                                                    </label>
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $index }}][status]" value="absent" class="status-radio text-red-600 focus:ring-red-500" {{ $status == 'absent' ? 'checked' : '' }}>
                                                        <span class="text-red-700 font-medium text-xs uppercase">Absent</span>
                                                    </label>
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $index }}][status]" value="late" class="status-radio text-yellow-600 focus:ring-yellow-500" {{ $status == 'late' ? 'checked' : '' }}>
                                                        <span class="text-yellow-700 font-medium text-xs uppercase">Late</span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" name="attendance[{{ $index }}][remark]" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Reason...">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-sm flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Attendance
                            </button>
                        </div>
                    </form>
                @endif
            </x-card>
        @endif
    @endif
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.mark-all');
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                const status = this.dataset.status;
                const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
                radios.forEach(r => r.checked = true);
            });
        });
    });
</script>
@endsection
@endsection
