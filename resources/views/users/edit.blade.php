@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Edit User" :breadcrumbs="[
        ['label' => 'User', 'url' => route('users.edit', $user)],
        ['label' => 'Edit User', 'url' => '#']
    ]" />

    <x-card>
        <div id="form-errors-container" class="hidden mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-100">
            <ul id="form-errors-list" class="list-disc list-inside text-sm"></ul>
        </div>
        
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="x-submit space-y-6" data-then="reload">
            @csrf
            
            <h5 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Edit User</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="name" value="{{ $user->name }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="w-full px-4 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="email" value="{{ $user->email }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="phone_number" value="{{ $user->phone_number }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Of Birth <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm datetimepicker" name="date_of_birth" placeholder="DD-MM-YYYY" value="{{ $user->date_of_birth }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select class="w-full px-4 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm select" name="status">
                        <option disabled>Select Status</option>
                        
                    </select>
                </div>
                
                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-4">
                        <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" name="avatar">
                        @if($user->avatar)
                            <img class="w-10 h-10 rounded-full object-cover border border-gray-200" src="{{ URL::to('/images/'. $user->avatar) }}">
                        @endif
                    </div>
                    <input type="hidden" name="hidden_avatar" value="{{ $user->avatar }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="position" value="{{ $user->position }}">
                </div>
                
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-100 mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Update</button>
            </div>
        </form>
    </x-card>
</div>
@endsection
