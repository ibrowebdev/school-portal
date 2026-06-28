@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Students" parent="Student" :parentRoute="route('students.index')" />

    <x-card noPadding="true">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Students</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.index') }}" class="w-10 h-10 border border-gray-200 text-gray-500 rounded-lg flex items-center justify-center hover:bg-gray-50 transition">
                    <i class="fa fa-list"></i>
                </a>
                <a href="{{ route('student/grid') }}" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center shadow-sm">
                    <i class="fa fa-th"></i>
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($studentList as $key=>$list)
                    <div class="bg-white border border-gray-100 rounded-xl p-6 text-center hover:shadow-md transition-shadow">
                        <a href="{{ url('student/profile/'.$list->id) }}" class="inline-block mb-4">
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-sm mx-auto" alt="Student Photo" src="{{ asset($list->upload) }}">
                        </a>
                        <h5 class="text-lg font-bold text-gray-800 mb-1">
                            <a href="{{ url('student/profile/'.$list->id) }}" class="hover:text-blue-600 transition">{{ $list->first_name }} {{ $list->last_name }}</a>
                        </h5>
                        <p class="text-sm text-gray-500 font-medium">Student</p>
                    </div>
                @endforeach
            </div>
        </div>
    </x-card>
</div>
@endsection
