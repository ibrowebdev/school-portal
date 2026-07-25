@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Teacher Details" parent="Teachers" :parentRoute="route('teachers.index')" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left Column: Profile Card -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    @if (!empty($teacher->avatar) && $teacher->avatar !== 'photo_defaults.jpg')
                        <img class="w-full h-full rounded-full object-cover border-4 border-blue-50" src="{{ URL::to('images/'.$teacher->avatar) }}" alt="{{ $teacher->name }}">
                    @else
                        <img class="w-full h-full rounded-full object-cover border-4 border-blue-50" src="{{ URL::to('images/photo_defaults.jpg') }}" alt="{{ $teacher->name }}">
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $teacher->first_name }} {{ $teacher->last_name }}</h2>
                <p class="text-gray-500 mb-4">{{ $teacher->teacherProfile->qualification ?? 'Teacher' }}</p>
                
                <div class="flex items-center justify-center gap-2 mb-6">
                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-sm font-medium">Active</span>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-3 text-left">
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="w-8 h-8 rounded bg-gray-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <div class="truncate">
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="font-medium truncate">{{ $teacher->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="w-8 h-8 rounded bg-gray-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Phone</p>
                            <p class="font-medium">{{ $teacher->phone_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Details & Classes -->
        <div class="md:col-span-2 space-y-6">
            <!-- About Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="font-medium text-gray-800">{{ ucfirst($teacher->gender) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="font-medium text-gray-800">{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Join Date</p>
                        <p class="font-medium text-gray-800">{{ $teacher->join_date ? \Carbon\Carbon::parse($teacher->join_date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Employee ID</p>
                        <p class="font-medium text-gray-800">{{ $teacher->teacherProfile->employee_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Experience</p>
                        <p class="font-medium text-gray-800">{{ $teacher->teacherProfile->experience ?? 'N/A' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium text-gray-800">
                            {{ collect([$teacher->teacherProfile->address ?? null, $teacher->teacherProfile->city ?? null, $teacher->teacherProfile->state ?? null, $teacher->teacherProfile->country ?? null])->filter()->join(', ') ?: 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Assigned Classes Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Assigned Classes & Subjects</h3>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-medium">{{ $assignments->count() }} Total</span>
                </div>
                
                @if($assignments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Class</th>
                                    <th class="px-6 py-4">Level</th>
                                    <th class="px-6 py-4">Capacity</th>
                                    <th class="px-6 py-4">Subject</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($assignments as $assignment)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-800">{{ $assignment->class_name }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $assignment->level ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $assignment->capacity ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-600">
                                            @if($assignment->subject_name)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                                                    {{ $assignment->subject_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chalkboard text-2xl text-gray-400"></i>
                        </div>
                        <p>No classes assigned to this teacher currently.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
