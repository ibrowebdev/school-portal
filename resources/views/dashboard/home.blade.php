@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Welcome {{ Session::get('name') }}!</h3>
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-medium">{{ Session::get('name') }}</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Students</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$stats['total_students']}}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-01.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
        <!-- Awards -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Classes</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$stats['total_classes']}}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-02.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
        <!-- Department -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Teachers</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$stats['total_teachers']}}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-03.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
        <!-- Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Current Session</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['current_session']->name }}</h3>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['current_session']->currentTerm->name }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Overview Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h5 class="font-bold text-gray-800">Overview</h5>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Teacher</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Student</div>
                    <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>
            <div class="p-6">
                <div id="apexcharts-area" class="h-[300px]"></div>
            </div>
        </div>
        <!-- Students Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h5 class="font-bold text-gray-800">Number of Students</h5>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Girls</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Boys</div>
                    <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>
            <div class="p-6">
                <div id="bar" class="h-[300px]"></div>
            </div>
        </div>
    </div>

</div>
@endsection
