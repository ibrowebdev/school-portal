@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Edit School Class" parent="Classes" :parentRoute="route('school-classes.index')" />

    <x-card>
        <form action="{{ route('school-classes.update', $schoolClass) }}" method="POST" class="x-submit" data-redirect="true">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ $schoolClass->name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                    <select name="level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Level</option>
                        <option value="junior" {{ $schoolClass->level == 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="senior" {{ $schoolClass->level == 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" name="capacity" value="{{ $schoolClass->capacity }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Update Class</button>
                <a href="{{ route('school-classes.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Cancel</a>
            </div>
        </form>
    </x-card>
</div>
@endsection
