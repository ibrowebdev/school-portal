@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Parent Dashboard" parent="Home" :parentRoute="route('home')" />

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Welcome, {{ auth()->user()->first_name }}!</h2>
        <p class="text-gray-500">Here is the overview of your children enrolled in our school.</p>
    </div>

    @if($children->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-400">
            <i class="fas fa-child text-5xl mb-4 block text-gray-300"></i>
            <h3 class="text-lg font-bold text-gray-700 mb-2">No Children Linked</h3>
            <p>You currently do not have any children linked to your parent account.</p>
            <p>Please contact the school administrator to link your children's profiles to your account.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($children as $child)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="h-24 bg-blue-600"></div>
                    <div class="px-6 pb-6 relative">
                        <div class="absolute -top-12 left-6">
                            <img src="{{ $child->user->avatar ? asset($child->user->avatar) : asset('photo_defaults.jpg') }}" class="w-24 h-24 rounded-full border-4 border-white object-cover shadow-sm bg-white">
                        </div>
                        <div class="pt-14 mb-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $child->user->name }}</h3>
                            <p class="text-blue-600 font-medium">{{ $child->schoolClass?->name }} {{ $child->classSection?->name }}</p>
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-id-card text-gray-400 w-5"></i> 
                                <span>Admission ID: <strong>{{ $child->admission_id }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-tint text-gray-400 w-5"></i> 
                                <span>Blood Group: {{ $child->blood_group ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('parent.child-profile', $child->user_id) }}" class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100 hover:border-gray-300 transition text-gray-700 group">
                                <i class="fas fa-user-circle text-xl mb-1 text-gray-400 group-hover:text-blue-500"></i>
                                <span class="text-xs font-bold uppercase tracking-wider">Profile</span>
                            </a>
                            <a href="{{ route('parent.child-results', $child->user_id) }}" class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100 hover:border-gray-300 transition text-gray-700 group">
                                <i class="fas fa-file-alt text-xl mb-1 text-gray-400 group-hover:text-green-500"></i>
                                <span class="text-xs font-bold uppercase tracking-wider">Results</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
