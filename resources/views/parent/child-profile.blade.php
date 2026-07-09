@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">{{ $child->name }} <span class="text-lg text-gray-500 font-normal">Profile</span></h2>
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <x-card noPadding="true">
                <div class="p-6 text-center border-b border-gray-100">
                    <img src="{{ $child->avatar ? asset($child->avatar) : asset('photo_defaults.jpg') }}" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-100 mb-4 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900">{{ $child->name }}</h3>
                    <p class="text-blue-600 font-medium">{{ $child->studentProfile->schoolClass?->name }} {{ $child->studentProfile->classSection?->name }}</p>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500 font-medium">Admission ID</span>
                        <span class="text-gray-800 font-bold">{{ $child->studentProfile->admission_id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500 font-medium">Roll Number</span>
                        <span class="text-gray-800 font-bold">{{ $child->studentProfile->roll_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500 font-medium">Gender</span>
                        <span class="text-gray-800 font-bold capitalize">{{ $child->gender }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500 font-medium">Date of Birth</span>
                        <span class="text-gray-800 font-bold">{{ \Carbon\Carbon::parse($child->date_of_birth)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500 font-medium">Blood Group</span>
                        <span class="text-gray-800 font-bold">{{ $child->studentProfile->blood_group ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Religion</span>
                        <span class="text-gray-800 font-bold">{{ $child->studentProfile->religion ?? 'N/A' }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            {{-- Quick Links --}}
            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                <a href="{{ route('parent.child-results', $child) }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-blue-400 hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl mr-4 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Academic Results</h4>
                        <p class="text-xs text-gray-500">View terminal reports</p>
                    </div>
                </a>
            </div>

            {{-- Attendance Overview --}}
            <x-card title="Current Term Attendance">
                @if(!empty($attendanceSummary) && $attendanceSummary['total'] > 0)
                    <div class="grid grid-cols-4 gap-3 text-center">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="text-sm text-gray-500 font-medium mb-1">Total Days</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $attendanceSummary['total'] }}</div>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                            <div class="text-sm text-green-700 font-medium mb-1">Present</div>
                            <div class="text-2xl font-bold text-green-700">{{ $attendanceSummary['present'] }}</div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                            <div class="text-sm text-red-700 font-medium mb-1">Absent</div>
                            <div class="text-2xl font-bold text-red-700">{{ $attendanceSummary['absent'] }}</div>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                            <div class="text-sm text-yellow-700 font-medium mb-1">Late</div>
                            <div class="text-2xl font-bold text-yellow-700">{{ $attendanceSummary['late'] }}</div>
                        </div>
                    </div>
                    
                    @php
                        $percentage = round(($attendanceSummary['present'] / $attendanceSummary['total']) * 100);
                    @endphp
                    <div class="mt-6">
                        <div class="flex justify-between text-sm font-medium mb-2">
                            <span class="text-gray-700">Attendance Rate</span>
                            <span class="{{ $percentage >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $percentage >= 75 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-calendar-times text-3xl mb-3 block"></i>
                        No attendance records found for the current term.
                    </div>
                @endif
            </x-card>

            {{-- Financial Overview --}}
            <livewire:student-financial-overview :student="$child" />
        </div>
    </div>
</div>
@endsection
