@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Grade Settings" parent="Results" :parentRoute="route('grade-settings.index')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Add/Edit Form --}}
        <div class="lg:col-span-1">
            <x-card title="Add New Grade">
                <form action="{{ route('grade-settings.store') }}" method="POST" class="x-submit" data-then="reload">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grade Letter <span class="text-red-500">*</span></label>
                            <input type="text" name="grade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" placeholder="e.g. A" required maxlength="5">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Score <span class="text-red-500">*</span></label>
                                <input type="number" name="min_score" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. 70" required min="0" max="100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Score <span class="text-red-500">*</span></label>
                                <input type="number" name="max_score" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. 100" required min="0" max="100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Remark <span class="text-red-500">*</span></label>
                            <input type="text" name="remark" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Excellent" required>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Save Grade</button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>

        {{-- Grading Scale List --}}
        <div class="lg:col-span-2">
            <x-card title="Grading Scale" noPadding="true">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Grade</th>
                                <th class="px-6 py-4">Score Range</th>
                                <th class="px-6 py-4">Remark</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($gradeSettings as $grade)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-lg text-blue-800">{{ $grade->grade }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-800">{{ $grade->min_score }} - {{ $grade->max_score }}</td>
                                    <td class="px-6 py-4 text-gray-600 italic">{{ $grade->remark }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('grade-settings.destroy', $grade) }}" method="POST" class="x-submit" data-confirm="Are you sure you want to delete it?" data-confirm-text="Delete" data-then="reload">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-list-ol text-4xl mb-3 block"></i>
                                        No grading scale defined yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</div>


@endsection
